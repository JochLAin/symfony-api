<?php 

namespace JochLAin\API\Mapping;

use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;

use JochLAin\API\Annotation\Criteria as AnnotationCriteria;
use JochLAin\API\Form\APIType;
use JochLAin\API\Mapping\Table;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Criteria
{
	const ASSOCIATION_TYPES = [
		ClassMetadata::ONE_TO_ONE, 
		ClassMetadata::MANY_TO_ONE, 
		ClassMetadata::ONE_TO_MANY, 
		ClassMetadata::MANY_TO_MANY
	];

	private $table = null;

	protected $name = '';
	protected $label = '';
	protected $type = '';
	protected $value = null;
	protected $options = [ 'attr' => [] ];

	public function __construct(Table $table) {
		$this->table = $table;
	} 

	public static function create(Table $table, string $name, string $label = '', string $type = '', $value = null, array $options = []) {
		$criteria = new Criteria($table);
		$criteria->setName($name);
		$criteria->setLabel($label);
		$criteria->setType($type);
		$criteria->setValue($value);
		$criteria->setOptions($options);
	}

	public function setName(string $name = null) { $this->name = $name; return $this; }
	public function getName() { return $this->name; }

	public function setLabel(string $label = null) { $this->label = $label; return $this; }
	public function getLabel() { return $this->label; }

	public function setType(string $type = null) { $this->type = $type; return $this; }
	public function getType() { return $this->type; }

	public function setValue($value = null) { $this->value = $value; return $this; }
	public function getValue() { return $this->value; }

	public function setOptions(array $options = []) { $this->options = $options; return $this; }
	public function getOptions() { return $this->options; }
	
	public function addOption(string $key, $value = null) { $this->options[$key] = $value; return $this; }
	public function removeOption(string $key) { unset($this->options[$key]); return $this; }

	public function addAttribute(string $key, $value = null) { $this->options['attr'][$key] = $value; return $this; }
	public function removeAttribute(string $key) { unset($this->options['attr'][$key]); return $this; }

	public function addData(string $key, $value = null) { $this->options['attr']['data-'.$key] = $value; return $this; }
	public function removeData(string $key) { unset($this->options['attr']['data-'.$key]); return $this; }

	public function mergeAnnotation(string $name, AnnotationCriteria $annotation) {
		$this->name = $annotation->getName() ?: $this->name ?: $name;
		$this->label = $annotation->getLabel() ?: $this->label;
		$this->type = $annotation->getType() ?: $this->type;
		$this->value = $annotation->getValue() ?: $this->value;
		$this->options = array_merge_recursive($this->options, $annotation->getOptions());

		// @TODO : Check the solidity of this feature with usecases
		if ($annotation->getName()) $this->options = array_merge([ 'mapped' => false ], $this->options);

		return $this;
	}

	public function improve(ClassMetadata $metadata) {
		if (!$this->name) return;
		$mapping = $this->getMapping($metadata, $this->name);

		if (!$this->type) {
			switch ($mapping['type']) {
				case ClassMetadata::ONE_TO_ONE:
					$this->mapRagnarok($mapping);
					break;
				case ClassMetadata::MANY_TO_ONE:
					$this->type = EntityType::class;
					$this->options = array_merge([ 
						'class' => $mapping['targetEntity'], 
						'choice_label' => 'id' 
					], $this->options);
					break;
				case ClassMetadata::ONE_TO_MANY:
					$this->mapRagnarokCollection($mapping);
					break;
				case ClassMetadata::MANY_TO_MANY:
					$this->type = EntityType::class;
					$this->options = array_merge([ 
						'class' => $mapping['targetEntity'], 
						'choice_label' => 'id', 
						'multiple' => true 
					], $this->options);
					break;

				case 'boolean':
					$this->type = CheckboxType::class;
					break;
				case 'integer':
				case 'smallint':
				case 'bigint':
				case 'decimal':
				case 'float':
					$this->type = NumberType::class;
					break;
				case 'text':
					$this->type = TextareaType::class;
					break;
				case 'array':
				case 'simple_array':
				case 'json_array':
				case 'object':
					$this->type = CollectionType::class;
					break;
				case 'date':
					$this->type = DateType::class;
					break;
				case 'time':
					$this->type = TimeType::class;
					break;
				case 'datetime':
					$this->type = DateTimeType::class;
					break;
				case 'datetimetz':
					$this->type = TimezoneType::class;
					break;
				case 'string':
				default:
					$this->type = TextType::class;
			}	
		} else {
			if (in_array($mapping['type'], self::ASSOCIATION_TYPES)) {
				if ($this->type == APIType::class) {
					$this->mapRagnarok($mapping);
				} else if ($this->type == CollectionType::class) {
					$this->mapRagnarokCollection($mapping);
				} 
			}
		}

		$this->options = array_merge($this->options, [ 'label' => $this->label, 'data' => $this->value ]);
		if (isset($mapping['nullable']) && $mapping['nullable']) $this->options = array_merge([ 'required' => false ], $this->options);

		if ($this->type == CollectionType::class) {
			// Add minimal override configuration on CollectionType
			$this->options = array_merge([
				'entry_type' => TextType::class,
				'allow_add' => true,
				'allow_delete' => true,
				'by_reference' => false
			], $this->options);
		}

		return $this;
	}

	protected function getMapping(ClassMetadata $metadata, string $name) {
		try {
			$mapping = $metadata->getAssociationMapping($name);
			return $mapping;
		} catch (\Exception $e) {
			$mapping = $metadata->getFieldMapping($name);
			return $mapping;
		}
	}

	private function mapRagnarok(array $mapping = []) {
		$this->type = APIType::class;
		$this->options = array_merge([ 
			'data_class' => $mapping['targetEntity'],
			'depth' => $this->form->getDepth() + 1
		], $this->options);
	}

	private function mapRagnarokCollection(array $mapping = []) {
		$this->type = CollectionType::class;
		$this->options = array_merge_recursive([
			'allow_add' => true,
			'allow_delete' => true,
			'entry_type' => APIType::class,
			'entry_options' => [ 
				'data_class' => $mapping['targetEntity'],
				'depth' => $this->form->getDepth() + 1
			]
		], $this->options);
	}
}