<?php

namespace JochLAin\API\Annotation;

use JochLAin\API\Annotation\Form;

/**
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Forms
{	
	/** @var array<JochLAin\API\Annotation\Form> */
	private $forms = [];

	public function __construct(array $data) {
		if (isset($data['value']) && is_array($data['value'])) $this->forms = $data['value'];
	}

	public function getForms() { return $this->forms; }
}