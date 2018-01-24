<?php

namespace Jochlain\API\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

use Jochlain\API\Form\Builder;
use Jochlain\API\Form\Encoder;
use Jochlain\Database\Manager\Query\FetchManager;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class ReadManager
{
    protected $em;
    protected $request;
    protected $stack;

    public function __construct(EntityManagerInterface $em, RequestStack $stack) {
        $this->em = $em;
        $this->request = $stack->getMasterRequest();
        $this->stack = $stack;
    }

    public function read(string $classname, $id, array $properties = null) {
        return ReadManager::reads($this->request, $this->em, $classname, $id, $properties);
    }

	public static function reads(Request $request, EntityManagerInterface $em, string $classname, $id, array $properties = null)
	{
        if ($id instanceof $classname) $id = $id->getId();

        $items = FetchManager::fetches($em, $classname, $properties, [], 0, 1, []);
        return isset($items[0]) ? $items[0] : null;
	}
}