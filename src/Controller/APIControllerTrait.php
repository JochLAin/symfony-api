<?php 

namespace Jochlain\API\Controller;

use Jochlain\API\Controller\CreateControllerTrait;
use Jochlain\API\Controller\DeleteControllerTrait;
use Jochlain\API\Controller\IndexControllerTrait;
use Jochlain\API\Controller\ReadControllerTrait;
use Jochlain\API\Controller\UpdateControllerTrait;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
trait APIControllerTrait
{
	use CreateControllerTrait;
	use DeleteControllerTrait;
	use IndexControllerTrait;
	use ReadControllerTrait;
	use UpdateControllerTrait;
}