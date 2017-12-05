<?php

namespace JochLAin\API\Annotation;

use JochLAin\API\Annotation\Table;

/**
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Tables
{	
	/** @var array<JochLAin\API\Annotation\Table> */
	private $tables = [];

	public function __construct(array $data) {
		if (isset($data['value']) && is_array($data['value'])) $this->tables = $data['value'];
	}

	public function getTables() { return $this->tables; }
}