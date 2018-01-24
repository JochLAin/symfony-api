<?php

namespace Jochlain\API\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

use Jochlain\API\Exception\FormException;
use Jochlain\API\Form\Builder;
use Jochlain\API\Form\Encoder;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class UpdateManager
{
    protected $em;
    protected $factory;
    protected $request;
    protected $stack;

    public function __construct(EntityManagerInterface $em, FormFactoryInterface $factory, RequestStack $stack) {
        $this->em = $em;
        $this->factory = $factory;
        $this->request = $stack->getMasterRequest();
        $this->stack = $stack;
    }

    public function update(string $classname, $data = null) {
        return UpdateManager::updates($this->request, $this->em, $this->factory, $classname, $data);
    }

	public static function updates(Request $request, EntityManagerInterface $em, FormFactoryInterface $factory, string $classname, $id)
	{
        if ($id instanceof $classname) $entity = $id;
        else $entity = $em->getRepository($classname)->find($id);

        $form = Builder::builds($factory, $classname, $entity);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if (!$form->isValid()) {
                throw new FormException($form);
            }
            $entity = $form->getData();
            $em->persist($entity);
            $em->flush();
            return;
        }

        return Encoder::encodes($form);
	}
}