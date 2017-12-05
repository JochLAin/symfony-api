<?php 

namespace JochLAin\API\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

use JochLAin\API\Manager\FetchManager;
use JochLAin\API\ORM\Repository;

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