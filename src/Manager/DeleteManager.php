<?php

namespace Jochlain\API\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

use Jochlain\API\Exception\FormException;
use Jochlain\API\Form\Encoder;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class DeleteManager
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

    public function delete(string $classname, $id) {
        return DeleteManager::deletes($this->request, $this->em, $this->factory, $classname, $id);
    }

	public static function deletes(Request $request, EntityManagerInterface $em, FormFactoryInterface $factory, string $classname, $id)
	{
        $form = $factory->createNamed('confirm-delete', FormType::class);
        if ($request->isMethod('DELETE')) {
            $form->handleRequest($request);
            if (!$form->isValid()) {
                throw new FormException($form);
            }
            if ($id instanceof $classname) $entity = $id;
            else $entity = $em->getRepository($classname)->find($id);

            $em->remove($entity);
            $em->flush();
            return;
        }
        return Encoder::encodes($form);
	}
}