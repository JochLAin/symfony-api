<?php 

namespace JochLAin\API\Manager;

use Doctrine\ORM\EntityManagerInterface;
use JochLAin\API\Manager\Query\CountManager;
use JochLAin\API\Manager\Query\FetchManager;
use JochLAin\API\Manager\Query\FilterManager;

class QueryManager 
{
    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function count(string $classname, array $contraints = []) {
        return CountManager::count($this->em, $classname, $constraints);
    }

    public function filter(string $classname, array $contraints = []) {
        return FilterManager::filter($this->em, $classname, $constraints);
    }

    public function fetch(string $classname, array $columns = null, array $constraints = [], int $offset = null, int $limit = null, array $sorts = []) {
        return FetchManager::fetch($this->em, $classname, $columns, $constraints, $offset, $limit, $sorts);
    }
}