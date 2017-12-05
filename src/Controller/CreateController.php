<?php

namespace JochLAin\API\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use JochLAin\API\Exception\FormException;
use JochLAin\API\Manager\CreateManager;

class CreateController implements ContainerAwareInterface 	
{
	use ControllerTrait;
	use ContainerAwareTrait;

	public static function create(Request $request, EntityManagerInterface $em, FormFactoryInterface $factory, string $classname, $data = null) 
	{
        try {
            $response = CreateManager::create($request, $em, $factory, $classname, $data);
            return new JsonResponse($response);
        } catch (FormException $e) {
            return new JsonResponse($e->getEncoded(), 422);
        }
	}
}