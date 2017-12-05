<?php

namespace JochLAin\API\Annotation;

use JochLAin\API\Annotation\Criteria;

/**
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