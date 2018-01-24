<?php

namespace Jochlain\API\Annotation;

use Jochlain\API\Annotation\Table;

/**
 * @namespace Jochlain\API\Annotation
 * @class Tables
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Tables
{	
	/** @var array<Jochlain\API\Annotation\Table> */
	private $tables = [];

	public function __construct(array $data) {
		if (isset($data['value']) && is_array($data['value'])) $this->tables = $data['value'];
	}

	public function getTables() { return $this->tables; }
}