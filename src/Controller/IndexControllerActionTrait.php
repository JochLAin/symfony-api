<?php

namespace Jochlain\API\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jochlain\API\Controller\IndexControllerTrait;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
trait IndexControllerActionTrait
{
	use IndexControllerTrait;

	/**
     * @Route("/index/", name="index")
     * @Method({"GET", "POST"})
     */
    public function indexAction() {
    	if (!$this->classname) {
    		throw new \Exception(
    			'Class "%s" must have property named "classname"',
    			get_class($this)
    		);
    	}
        return $this->index($this->classname, $this->properties);
    }
}