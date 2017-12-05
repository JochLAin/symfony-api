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
use JochLAin\API\Manager\UpdateManager;

class UpdateController implements ContainerAwareInterface
{
	use ControllerTrait;
	use ContainerAwareTrait;

	public static function update(Request $request, EntityManagerInterface $em, FormFactoryInterface $factory, string $classname, $id) 
	{
        try {
            $response = UpdateManager::update($request, $em, $factory, $classname, $id);
            return new JsonResponse($response);
        } catch (FormException $e) {
            return new JsonResponse($e->getEncoded(), 422);
        }
	}
}