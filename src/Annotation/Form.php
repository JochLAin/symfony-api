<?php

namespace Jochlain\API\Annotation;

/**
 * @namespace Jochlain\API\Annotation
 * @class Form
 *
 * @Annotation
 * @Target({"ANNOTATION","CLASS"})
 *
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Form
{
	/** @var string */
	private $name = 'default';

	/** @var string */
	private $type = '';

	/** @var array */
	private $options = [];

	/** @var array<Jochlain\API\Annotation\Field> */
	private $fields = [];

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

	public function setType(string $type = null) { $this->type = $type; return $this; }
	public function getType() { return $this->type; }

	public function setOptions(array $options) { $this->options = $options; return $this; }
	public function getOptions() { return $this->options; }

	public function setFields(array $fields) { $this->fields = $fields; return $this; }
	public function getFields() { return $this->fields; }
}
