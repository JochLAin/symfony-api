<?php

namespace Jochlain\API\Controller;

use Jochlain\API\Controller\CreateControllerActionTrait;
use Jochlain\API\Controller\DeleteControllerActionTrait;
use Jochlain\API\Controller\IndexControllerActionTrait;
use Jochlain\API\Controller\ReadControllerActionTrait;
use Jochlain\API\Controller\UpdateControllerActionTrait;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
trait APIControllerActionTrait
{
	use CreateControllerActionTrait;
	use DeleteControllerActionTrait;
	use IndexControllerActionTrait;
	use ReadControllerActionTrait;
	use UpdateControllerActionTrait;
}