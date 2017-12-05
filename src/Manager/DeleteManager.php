<?php

namespace JochLAin\API\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

use JochLAin\API\Exception\FormException;
use JochLAin\API\Form\Encoder;

class DeleteManager
{
	public static function delete(Request $request, EntityManagerInterface $em, FormFactoryInterface $factory, string $classname, $id) 
	{
        $form = $factory->createForm();
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
        return Encoder::encode($form);
	}
}