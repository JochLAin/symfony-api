<?php

namespace JochLAin\API\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use JochLAin\API\Controller\DeleteControllerTrait;

trait DeleteControllerActionTrait
{
	use DeleteControllerTrait;

	/**
     * @Route("/delete/{id}/", name="delete", requirements={"id"="\d+"})
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction(int $id) {
    	if (!$this->classname) {
    		throw new \Exception(
    			'Class "%s" must have property named "classname"', 
    			get_class($this)
    		);
    	}
        return $this->delete($this->classname, $id, $this->right); 
    }
}