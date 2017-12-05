<?php

namespace JochLAin\API\Annotation;

/**
 * @Annotation
 * @Target({"ANNOTATION", "CLASS"})
 */
class Table
{
	/** @var string */
	private $name = 'default';

	/** @var string */
	private $title = '';

	/** @var array<JochLAin\API\Annotation\Column> */
	private $columns = [];

	/** @var array<JochLAin\API\Annotation\Criteria> */
	private $criterias = [];

	public function __construct(array $data) {
		if (isset($data['value'])) {
			if (is_string($data['value'])) $data['name'] = $data['value'];
			unset($data['value']);
		}

		foreach ($data as $key => $value) {
			$method = 'set'.str_replace('_', '', $key);
			if (!method_exists($this, $method)) {
				throw new \BadMEthodCallException(sprintf('Unknow property "%s" on annotation "%s"', $key, get_class($this)));
			}
			$this->$method($value);
		}
	}

	public function setName(string $name = null) { $this->name = $name; return $this; }
	public function getName() { return $this->name; }
	
	public function setTitle(string $title = null) { $this->title = $title; return $this; }
	public function getTitle() { return $this->title; }
	
	public function setColumns(array $columns = []) { $this->columns = $columns; return $this; }
	public function getColumns() { return $this->columns; }

	public function setCriterias(array $criterias = []) { $this->criterias = $criterias; return $this; }
	public function getCriterias() { return $this->criterias; }
}