<?php

namespace Jochlain\API\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Jochlain\API\Controller\IndexController;
use Jochlain\API\Manager\CatalogManager;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
trait IndexControllerTrait
{
	private function index(string $classname, array $properties = [], array $constraints = [], array $sorts = [])
	{
		if (!$this instanceof ContainerAwareInterface) {
			throw new \Exception(sprintf(
				'Class "%s" must implements "%s"',
				get_class($this),
				ContainerAwareInterface::class
			));
		}

		$em = $this->container->get('doctrine.orm.entity_manager');
		$request = $this->container->get('request_stack')->getCurrentRequest();
		$cm = $this->container->get(CatalogManager::class);
        return IndexController::indexes($request, $em, $cm, $classname, $properties, $constraints, $sorts);
	}
}