<?php

namespace JochLAin\API\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use JochLAin\API\Manager\ReadManager;

class ReadController implements ContainerAwareInterface
{
	use ControllerTrait;
	use ContainerAwareTrait;

	public static function read(Request $request, EntityManagerInterface $em, string $classname, $id) 
	{
        return new JsonResponse(ReadManager::read($request, $em, $classname, $id));
	}
}