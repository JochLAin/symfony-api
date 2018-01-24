<?php

namespace Jochlain\API\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Jochlain\API\Controller\CreateController;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
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
        return CreateController::creates($request, $em, $factory, $classname, $data);
	}
}