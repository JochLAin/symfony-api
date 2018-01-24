<?php

namespace Jochlain\API\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Jochlain\API\Controller\ReadController;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
trait ReadControllerTrait
{
	private function read(string $classname, $id, array $properties = null)
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
        return ReadController::reads($request, $em, $classname, $id, $properties);
	}
}