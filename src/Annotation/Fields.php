<?php

namespace Jochlain\API\Annotation;

use Jochlain\API\Annotation\Field;

/**
 * @namespace Jochlain\API\Annotation
 * @class Field
 *
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 *
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Fields
{	
	/** @var array<Jochlain\API\Annotation\Field> */
	private $fields = [];

	public function __construct(array $data) {
		if (isset($data['value']) && is_array($data['value'])) $this->fields = $data['value'];
	}

	public function getFields() { return $this->fields; }
}