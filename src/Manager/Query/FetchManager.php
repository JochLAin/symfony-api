<?php 

namespace JochLAin\API\Manager\Query;

use Doctrine\ORM\EntityManagerInterface;
use JochLAin\API\ORM\Repository;

class FetchManager {
    public static function fetch(EntityManagerInterface $em, string $classname, array $columns = null, array $constraints = [], int $offset = null, int $limit = null, array $sorts = []) {
        $configuration = $em->getConfiguration();
        $default = $configuration->getDefaultRepositoryClassName();
        $configuration->setDefaultRepositoryClassName(Repository::class);

        $repository = $em->getRepository($classname);
        $entities = $repository->fetch($columns, $constraints, null, $offset, $limit, $sorts);

        $configuration->setDefaultRepositoryClassName($default);
        return $entities;
    }
}