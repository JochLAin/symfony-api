<?php

namespace JochLAin\API\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use JochLAin\API\Controller\IndexController;

trait IndexControllerTrait
{
	private function index(string $classname) 
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
        return IndexController::index($request, $em, $classname);
	}
}