<?php

namespace JochLAin\API\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

use JochLAin\API\Exception\FormException;
use JochLAin\API\Form\Builder;
use JochLAin\API\Form\Encoder;

class UpdateManager
{
	public static function update(Request $request, EntityManagerInterface $em, FormFactoryInterface $factory, string $classname, $id) 
	{
        if ($id instanceof $classname) $entity = $id;
        else $entity = $em->getRepository($classname)->find($id);

        $form = Builder::build($factory, $classname, $entity);

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

        return Encoder::encode($form);
	}
}