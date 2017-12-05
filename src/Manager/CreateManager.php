<?php

namespace JochLAin\API\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

use JochLAin\API\Exception\FormException;
use JochLAin\API\Form\Builder;
use JochLAin\API\Form\Encoder;

class CreateManager
{
	public static function create(Request $request, EntityManagerInterface $em, FormFactoryInterface $factory, string $classname, $data = null) 
	{
        $form = Builder::build($factory, $classname, $data);

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