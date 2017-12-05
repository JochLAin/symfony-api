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
use Symfony\Component\Form\FormBuilderInterface;

use JochLAin\API\ORM\Repository;
use JochLAin\Security\Entity\Member;
use JochLAin\API\Annotation\Form as AnnotationForm;
use JochLAin\API\Annotation\Field as AnnotationField;
use JochLAin\API\Form\TypeType;
use JochLAin\API\Mapping\Form;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Field
{
	const ASSOCIATION_TYPES = [
		ClassMetadata::ONE_TO_ONE, 
		ClassMetadata::MANY_TO_ONE, 
		ClassMetadata::ONE_TO_MANY, 
		ClassMetadata::MANY_TO_MANY
	];

	private $form = null;
	private $user = null;

	protected $name = '';
	protected $label = '';
	protected $type = '';
	protected $value = null;
	protected $options = [ 'attr' => [] ];

	public function __construct(Form $form, Member $user = null) {
		$this->form = $form;
		$this->user = $user;
	}

	public static function create(Form $form, string $name, string $label = '', string $type = '', $value = null, array $options = []) {
		$field = new Field($form);
		$field->setName($name);
		$field->setLabel($label);
		$field->setType($type);
		$field->setValue($value);
		$field->setOptions($options);
	}

	public function getForm() { return $this->form; }

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

	public function mergeAnnotation(string $name, AnnotationField $annotation) {
		$this->name = $annotation->getName() ?: $this->name ?: $name;
		$this->label = $annotation->getLabel() ?: $this->label;
		$this->type = $annotation->getType() ?: $this->type;
		$this->value = $annotation->getValue() ?: $this->value;
		$this->options = array_merge_recursive($this->options, $annotation->getOptions());

		// @TODO : Check the solidity of this feature with usecases
		if ($annotation->getName()) $this->options = array_merge([ 'mapped' => false ], $this->options);

		return $this;
	}

	public function improve(ClassMetadata $metadata, FormBuilderInterface $builder) {
		if (!$this->name) return;
		$mapping = $this->getMapping($metadata, $this->name);

		if (!$this->type) {
			switch ($mapping['type']) {
				case ClassMetadata::ONE_TO_ONE:
					$this->mapRagnarok($mapping);
					break;
				case ClassMetadata::MANY_TO_ONE:
					$this->mapEntity($builder, $this->form->getOptions(), $mapping, false);
					break;
				case ClassMetadata::ONE_TO_MANY:
					$this->mapRagnarokCollection($mapping);
					break;
				case ClassMetadata::MANY_TO_MANY:
					$this->mapEntity($builder, $this->form->getOptions(), $mapping, true);
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
				} else if ($this->type == EntityType::class) {
					$this->mapEntity($builder, $this->form->getOptions(), $mapping, $mapping['type'] == ClassMetadata::MANY_TO_MANY);
				}
			} else if ($mapping['type'] == ChoiceType::class) {
				if (isset($this->options['choices']) && is_callable($this->options['choices'])) {
					$this->options['choices'] = call_user_func($this->user, $builder->getData(), $this->form->getOptions());
				}
			}
		}

		$this->options = array_merge($this->options, [ 'label' => $this->label ]);
		if ($this->value) $this->options = array_merge($this->options, [ 'data' => $this->value ]);
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

	private function mapEntity(FormBuilderInterface $builder, array $options, array $mapping = [], bool $multiple = false) {
		$query = !isset($this->options['query_builder']) ? null : $this->options['query_builder'];
		$choices = !isset($this->options['choices']) ? null : $this->options['choices'];
		if (is_callable($choices)) $choices = call_user_func($choices, $this->user, $builder->getData(), $options);

		$this->type = EntityType::class;
		$this->options = array_merge([ 
			'class' => $mapping['targetEntity'], 
			'choice_label' => 'id', 
			'multiple' => $multiple, 
		], $this->options, [
			'choices' => $choices,
			'query_builder' => !$query ? null : function (Repository $repository) use ($builder, $query, $options) { 
				if (is_string($query)) $query = [$repository, $query];
				$parameters = array_slice($query, 2);
				$query = array_slice($query, 0, 2);
				return call_user_func($query, $repository, $this->user, $builder->getData(), $options, ...$parameters); 
			}
		]);
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