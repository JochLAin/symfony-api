<?php

namespace JochLAin\API\Mapping;

use JochLAin\API\Annotation\Column as AnnotationColumn;
use JochLAin\API\Mapping\Table;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Column
{
	private $table;

	protected $name;
	protected $label;

	public function __construct(Table $table) {
		$this->table = $table;
	} 

	public static function create(Table $table, string $name, string $label = '', string $type = '', $value = null, array $options = []) {
		$column = new Criteria($table);
		$column->setName($name);
		$column->setLabel($label);
	}

	public function setName(string $name = null) { $this->name = $name; return $this; }
	public function getName() { return $this->name; }

	public function setLabel(string $label = null) { $this->label = $label; return $this; }
	public function getLabel() { return $this->label; }

	public function mergeAnnotation(string $name, AnnotationColumn $annotation) {
		$this->name = $annotation->getName() ?: $this->name ?: $name;
		$this->label = $annotation->getLabel() ?: $this->label;
		return $this;
	}
}