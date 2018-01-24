<?php

namespace Jochlain\API\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Jochlain\API\Manager\CatalogManager;
use Jochlain\API\Manager\ReadManager;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class ReadController implements ContainerAwareInterface
{
	use ControllerTrait;
	use ContainerAwareTrait;

	public static function reads(Request $request, EntityManagerInterface $em, string $classname, $id, array $properties = null)
	{
        return new JsonResponse(ReadManager::reads($request, $em, $classname, $id, $properties));
	}
}