<?php

namespace Jochlain\API\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Jochlain\API\Exception\FormException;
use Jochlain\API\Manager\CreateManager;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class CreateController implements ContainerAwareInterface
{
	use ControllerTrait;
	use ContainerAwareTrait;

	public static function creates(Request $request, EntityManagerInterface $em, FormFactoryInterface $factory, string $classname, $data = null)
	{
        try {
            $response = CreateManager::creates($request, $em, $factory, $classname, $data);
            return new JsonResponse($response);
        } catch (FormException $e) {
            return new JsonResponse($e->getEncoded(), 422);
        }
	}
}