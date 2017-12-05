<?php

namespace JochLAin\API\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use JochLAin\API\Form\Builder;
use JochLAin\API\Form\Encoder;

class ReadManager
{
	public static function read(Request $request, EntityManagerInterface $em, string $classname, $id) 
	{
        if ($id instanceof $classname) $entity = $id;
        else $entity = $em->getRepository($classname)->find($id);

        return $classname::toArray($entity);
	}
}