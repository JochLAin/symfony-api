<?php 

namespace JochLAin\API\Mapping;

use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;

use JochLAin\API\Annotation\Form as AnnotationForm;
use JochLAin\API\Form\APIType;
use JochLAin\API\Mapping\Field;

class Form
{
	private $metadata = null;
	private $depth = 0;
	private $fields = [];

	protected $name = 'default';
	protected $type = APIType::class;
	protected $options = [];

	public function __construct(ClassMetadata $metadata, int $depth) {
		$this->metadata = $metadata;
		$this->depth = $depth;
	}

	public function build(FormFactoryInterface $factory, $data = null) {
		return $factory->create($this->type, $data, array_merge(
			[ 'data_class' => $this->metadata->getName() ], 
			$this->options
		));
	}

	public static function buildForm(FormBuilderInterface &$builder, Form $form, $data, string $key = null) {
		if (!$form->getType()) return;
		foreach ($form->getFields() as $field) {
			if ($key && $key != $field->getName()) continue;
			$field->improve($form->getMetadata(), $builder);
			$builder->add($field->getName(), $field->getType(), $field->getOptions());
		}
	}

	public function getMetadata() { return $this->metadata; }

	public function isDefault() { return $this->default; }

	public function getDepth() { return $this->depth; }

	public function setName(string $name = null) { $this->name = $name; return $this; }
	public function getName() { return $this->name; }

	public function setType(string $type = null) { $this->type = $type; return $this; }
	public function getType() { return $this->type; }

	public function getOptions() { return $this->options; }
	public function mergeOptions(array $options = []) { $this->options = array_merge($this->options, $options); return $this; }

	public function setFields(array $fields = []) { $this->fields = $fields; return $this; }
	public function getFields() { return $this->fields; }
	public function addField(Field $field) { $this->fields[$field->getName()] = $field; return $this; }
	public function removeField(string $name) { unset($this->fields[$name]); }

	public function mergeAnnotation(AnnotationForm $annotationForm = null) {
		if (!$annotationForm) return;
		$this->name = $name;
		$this->type = $type;
		$this->options = $options;
	}
}