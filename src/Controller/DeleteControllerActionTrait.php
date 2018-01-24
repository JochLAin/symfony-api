<?php

namespace Jochlain\API\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jochlain\API\Controller\DeleteControllerTrait;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
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