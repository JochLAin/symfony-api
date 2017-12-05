<?php 

namespace JochLAin\API\Manager\Query;

use Doctrine\ORM\EntityManagerInterface;
use JochLAin\API\ORM\Repository;

class CountManager {
    public static function count(EntityManagerInterface $em, string $classname, array $constraints = []) {
        $configuration = $em->getConfiguration();
        $default = $configuration->getDefaultRepositoryClassName();
        $configuration->setDefaultRepositoryClassName(Repository::class);

        $repository = $em->getRepository($classname);
        $entities = $repository->count($constraints);

        $configuration->setDefaultRepositoryClassName($default);
        return $entities;
    }
}