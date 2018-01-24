<?php

namespace Jochlain\API\Annotation;

use Jochlain\API\Annotation\Criteria;

/**
 * @namespace Jochlain\API\Annotation
 * @class Criterias
 *
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 *
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Criterias
{	
	/** @var array<RagnarokBundle\TableBundle\Annotation\Criteria> */
	private $criterias = [];

	public function __construct(array $data) {
		if (isset($data['value']) && is_array($data['value'])) $this->criterias = $data['value'];
	}

	public function getCriterias() { return $this->criterias; }
}