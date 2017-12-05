<?php

namespace JochLAin\API\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use JochLAin\API\Controller\CreateController;

trait CreateControllerTrait
{
	private function create(string $classname, $data = null) 
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
		$factory = $this->container->get('form.factory');
        return CreateController::create($request, $em, $factory, $classname, $data);
	}
}