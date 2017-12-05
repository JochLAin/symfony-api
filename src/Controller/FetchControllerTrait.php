<?php

namespace JochLAin\API\Controller;

use JochLAin\API\Response\FetchResponse;

trait FetchControllerTrait {
    protected function fetch($repository, array $columns, array $constraints, int $status = 200, $headers = []) {
        if (is_string($repository)) $repository = $this->container->get('doctrine.orm.entity_manager')->getRepository($repository);
        return new FetchResponse($repository, $columns, $constraints, $status, $headers);
    }
}