<?php 

namespace JochLAin\API\Controller;

use JochLAin\API\Controller\CreateControllerTrait;
use JochLAin\API\Controller\DeleteControllerTrait;
use JochLAin\API\Controller\IndexControllerTrait;
use JochLAin\API\Controller\ReadControllerTrait;
use JochLAin\API\Controller\UpdateControllerTrait;

trait APIControllerTrait
{
	use CreateControllerTrait;
	use DeleteControllerTrait;
	use IndexControllerTrait;
	use ReadControllerTrait;
	use UpdateControllerTrait;
}