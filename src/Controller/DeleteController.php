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
use Jochlain\API\Manager\DeleteManager;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class DeleteController implements ContainerAwareInterface
{
	use ControllerTrait;
	use ContainerAwareTrait;

	public static function deletes(Request $request, EntityManagerInterface $em, FormFactoryInterface $factory, string $classname, $id)
	{
        try {
            $response = DeleteManager::deletes($request, $em, $factory, $classname, $id);
            return new JsonResponse($response);
        } catch (FormException $e) {
            return new JsonResponse($e->getEncoded(), 422);
        }
	}
}