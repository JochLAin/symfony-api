<?php

namespace JochLAin\API\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use JochLAin\API\Controller\ReadController;

trait ReadControllerTrait
{
	private function read(string $classname, $id) 
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
        return ReadController::read($request, $em, $classname, $id);
	}
}