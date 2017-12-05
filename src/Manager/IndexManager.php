<?php

namespace JochLAin\API\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use JochLAin\API\Form\Builder;
use JochLAin\API\Form\Encoder;

class IndexManager
{
	public static function index(Request $request, EntityManagerInterface $em, string $classname) 
	{
        $entities = $em->getRepository($classname)->findAll();
        return $classname::toArray($entities);
	}
}