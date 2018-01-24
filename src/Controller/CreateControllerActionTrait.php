<?php

namespace Jochlain\API\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jochlain\API\Controller\CreateControllerTrait;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
trait CreateControllerActionTrait
{
	use CreateControllerTrait;

	/**
     * @Route("/create/", name="create")
     * @Method({"GET", "POST"})
     */
    public function createAction() {
    	if (!$this->classname) {
    		throw new \Exception(
    			'Class "%s" must have property named "classname"',
    			get_class($this)
    		);
    	}
        return $this->create($this->classname, $this->right);
    }
}