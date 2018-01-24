<?php

namespace Jochlain\API\Annotation;

/**
 * @namespace Jochlain\API\Annotation
 * @class Field
 *
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 *
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Field
{
	/** @var string */
	private $name = '';

	/** @var string */
	private $label = '';

	/** @var string */
	private $type = null;

	/** @var mixed */
	private $value = null;

	/** @var array */
	private $options = [];

	/** @var array<string> */
	private $forms = [];

	public function __construct(array $data) {
		if (isset($data['value'])) {
			if (is_string($data['value'])) $data['type'] = $data['value'];
			else if (is_array($data['value'])) $data['options'] = $data['value'];
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

	public function setType(string $type = null) { $this->type = $type; return $this; }
	public function getType() { return $this->type; }

	public function setValue($value) { $this->value = $value; return $this; }
	public function getValue() { return $this->value; }

	public function setOptions(array $options = []) { $this->options = $options; return $this; }
	public function getOptions() { return $this->options; }

	public function setForms(array $forms = []) { $this->forms = $forms; return $this; }
	public function getForms() { return $this->forms; }
}
