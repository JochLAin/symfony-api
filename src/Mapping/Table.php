<?php

namespace Jochlain\API\Mapping;

use Doctrine\ORM\Mapping\ClassMetadata;

use Jochlain\API\Annotation\Table as AnnotationTable;
use Jochlain\API\Mapping\Column;
use Jochlain\API\Mapping\Criteria;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Table
{
	private $metadata = null;
	private $depth = 0;
	private $columns = [];
	private $criterias = [];

	protected $name = 'default';
	protected $title = '';

	public function __construct(ClassMetadata $metadata, int $depth) {
		$this->metadata = $metadata;
		$this->depth = $depth;
	}

	public function getMetadata() { return $this->metadata; }

	public function isDefault() { return $this->default; }

	public function getDepth() { return $this->depth; }

	public function setName(string $name = null) { $this->name = $name; return $this; }
	public function getName() { return $this->name; }

	public function setTitle(string $title = null) { $this->title = $title; return $this; }
	public function getTitle() { return $this->title; }

	public function setColumns(array $columns = []) { $this->columns = $columns; return $this; }
	public function getColumns() { return $this->columns; }
	public function addColumn(Column $column) { $this->columns[$column->getName()] = $column; return $this; }
	public function removeColumn(string $name) { unset($this->columns[$name]); }

	public function setCriterias(array $criterias = []) { $this->criterias = $criterias; return $this; }
	public function getCriterias() { return $this->criterias; }
	public function addCriteria(Criteria $criteria) { $this->criterias[$criteria->getName()] = $criteria; return $this; }
	public function removeCriteria(string $name) { unset($this->criterias[$name]); }

	public function mergeAnnotation(AnnotationTable $annotation = null) {
		if (!$annotation) return;
		$this->setName($annotation->getName() ?: $this->getName());
		$this->setTitle($annotation->getTitle() ?: $this->getTitle());
	}
}