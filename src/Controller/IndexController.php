<?php

namespace Jochlain\API\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Jochlain\API\Manager\CatalogManager;
use Jochlain\API\Manager\IndexManager;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class IndexController implements ContainerAwareInterface
{
	use ControllerTrait;
	use ContainerAwareTrait;

	public static function indexes(Request $request, EntityManagerInterface $em, CatalogManager $cm, string $classname, array $properties)
	{
        return new JsonResponse(IndexManager::indexes($request, $em, $cm, $classname, $properties));
	}
}