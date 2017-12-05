<?php 

namespace JochLAin\API\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 *
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Column
{
	/** @var string */
	private $name = '';

	/** @var string */
	private $label = '';

	/** @var array */
	private $tables;

	public function __construct(array $data) {
		if (isset($data['value'])) {
			if (is_string($data['value'])) $data['label'] = $data['value'];
			else if (is_array($data['value'])) $data['tables'] = $data['value'];
			unset($data['value']);
		}

		foreach ($data as $key => $value) {
			$method = 'set'.str_replace('_', '', ucfirst($key));
			if (!method_exists($this, $method)) {
				throw new \BadMEthodCallException(sprintf('Unknow property "%s" on annotation "%s"', $key, get_class($this)));
			}
			$this->$method($value);
		}
	}

	public function setName(string $name = null) { $this->name = $name; return $this; }
	public function getName() { return $this->name; }

	public function setLabel(string $label = null) { $this->label = $label; return $this; }
	public function getLabel() { return $this->label; }

	public function setTables(array $tables = []) { $this->tables = $tables; return $this; }
	public function getTables() { return $this->tables; }
}