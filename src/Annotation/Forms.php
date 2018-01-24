<?php

namespace Jochlain\API\Annotation;

use Jochlain\API\Annotation\Form;

/**
 * @namespace Jochlain\API\Annotation
 * @class Forms
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Forms
{	
	/** @var array<Jochlain\API\Annotation\Form> */
	private $forms = [];

	public function __construct(array $data) {
		if (isset($data['value']) && is_array($data['value'])) $this->forms = $data['value'];
	}

	public function getForms() { return $this->forms; }
}