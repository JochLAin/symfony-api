<?php

namespace JochLAin\API\Annotation;

use JochLAin\API\Annotation\Field;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 *
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Fields
{	
	/** @var array<JochLAin\API\Annotation\Field> */
	private $fields = [];

	public function __construct(array $data) {
		if (isset($data['value']) && is_array($data['value'])) $this->fields = $data['value'];
	}

	public function getFields() { return $this->fields; }
}