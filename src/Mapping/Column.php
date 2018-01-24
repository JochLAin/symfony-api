<?php

namespace Jochlain\API\Mapping;

use Jochlain\API\Annotation\Column as AnnotationColumn;
use Jochlain\API\Mapping\Table;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Column
{
	private $table;

	protected $name;
	protected $label;
	protected $visible;
	protected $sort;

	public function __construct(Table $table) {
		$this->table = $table;
	} 

	public static function create(Table $table, string $name, string $label = '', bool $visble = false, string $sort = null) {
		$column = new Column($table);
		$column->setName($name);
		$column->setLabel($label);
		$column->setVisible($visible);
		$column->setSort($sort);
	}

	public function setName(string $name = null) { $this->name = $name; return $this; }
	public function getName() { return $this->name; }

	public function setLabel(string $label = null) { $this->label = $label; return $this; }
	public function getLabel() { return $this->label; }

	public function setVisible(bool $visible = null) { $this->visible = $visible; return $this; }
	public function getVisible() { return $this->visible; }
	public function isVisible() { return (bool)$this->visible; }

	public function setSort(string $sort = null) { $this->sort = $sort; return $this; }
	public function getSort() { return $this->sort; }

	public function mergeAnnotation(string $name, AnnotationColumn $annotation) {
		$this->setName($annotation->getName() ?: $this->getName() ?: $name);
		$this->setLabel($annotation->getLabel() ?: $this->getLabel());
		$this->setVisible($annotation->getVisible() !== null ? $annotation->isVisible() : $this->isVisible());
		$this->setSort($annotation->getSort() ?: $this->getSort());
		return $this;
	}
}