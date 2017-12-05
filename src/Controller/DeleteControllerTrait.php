<?php

namespace JochLAin\API\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use JochLAin\API\Controller\DeleteController;

trait DeleteControllerTrait
{
	private function delete(string $classname, $id) 
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
        return DeleteController::delete($request, $em, $factory, $classname, $id);
	}
}