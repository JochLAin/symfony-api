<?php

namespace Jochlain\API\Controller;

use Jochlain\API\Response\FetchResponse;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
trait FetchControllerTrait {
    protected function fetch($repository, array $columns, array $constraints, int $status = 200, $headers = []) {
        if (is_string($repository)) $repository = $this->container->get('doctrine.orm.entity_manager')->getRepository($repository);
        return new FetchResponse($repository, $columns, $constraints, $status, $headers);
    }
}