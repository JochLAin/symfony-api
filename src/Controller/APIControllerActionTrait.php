<?php 

namespace JochLAin\API\Controller;

use JochLAin\API\Controller\CreateControllerActionTrait;
use JochLAin\API\Controller\DeleteControllerActionTrait;
use JochLAin\API\Controller\IndexControllerActionTrait;
use JochLAin\API\Controller\ReadControllerActionTrait;
use JochLAin\API\Controller\UpdateControllerActionTrait;

trait APIControllerActionTrait
{
	use CreateControllerActionTrait;
	use DeleteControllerActionTrait;
	use IndexControllerActionTrait;
	use ReadControllerActionTrait;
	use UpdateControllerActionTrait;
}