<?php

namespace JochLAin\API\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use JochLAin\API\Controller\ReadControllerTrait;

trait ReadControllerActionTrait
{
	use ReadControllerTrait;

	/**
     * @Route("/{id}/", name="read", requirements={"id"="\d+"})
     * @Method({"GET"})
     */
    public function readAction(int $id) {
    	if (!$this->classname) {
    		throw new \Exception(
    			'Class "%s" must have property named "classname"', 
    			get_class($this)
    		);
    	}
        return $this->read($this->classname, $id, $this->right); 
    }
}