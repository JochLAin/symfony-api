<?php

namespace JochLAin\API\Annotation;

use JochLAin\API\Annotation\Column;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 *
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Columns
{	
	/** @var array<RagnarokBundle\TableBundle\Annotation\Column> */
	private $columns = [];

	public function __construct(array $data) {
		if (isset($data['value']) && is_array($data['value'])) $this->columns = $data['value'];
	}

	public function getColumns() { return $this->columns; }
}