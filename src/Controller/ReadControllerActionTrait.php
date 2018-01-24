<?php

namespace Jochlain\API\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jochlain\API\Controller\ReadControllerTrait;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
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
        return $this->read($this->classname, $id, $this->right, $this->properties);
    }
}