<?php

namespace JochLAin\API\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use JochLAin\API\Controller\CreateControllerTrait;

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