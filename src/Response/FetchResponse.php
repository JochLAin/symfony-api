<?php

namespace Jochlain\API\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

use Jochlain\API\Manager\FetchManager;
use Jochlain\Database\ORM\Repository;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class FetchResponse extends JsonResponse
{
    public function __construct(string $classname, array $columns, array $constraints, int $status = 200, $headers = []) {
        $datas = $repository->fetch($columns, $constraints);
        parent::__construct($datas, $status, $headers);
    }

    public static function create($data = [], $status = 200, $headers = []) {
        return new static($data['classname'], $data['columns'], $data['constraints'], $status, $headers);
    }
}