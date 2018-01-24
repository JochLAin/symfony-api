<?php

namespace Jochlain\API\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jochlain\API\Controller\UpdateControllerTrait;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
trait UpdateControllerActionTrait
{
	use UpdateControllerTrait;

	/**
     * @Route("/update/{id}/", name="update", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     */
    public function updateAction(int $id) {
    	if (!$this->classname) {
    		throw new \Exception(
    			'Class "%s" must have property named "classname"',
    			get_class($this)
    		);
    	}
        return $this->update($this->classname, $id, $this->right);
    }
}