<?php

namespace Jochlain\API\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormView;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Encoder
{
	public static function encodes(Form $form, array $errors = null)
	{
		if (!$errors) $errors = Encoder::getErrors($form->getName(), $form->getErrors(true, false));
		$view = $form->createView();
		return Encoder::getView($view, null, $errors, $view);
	}

	public static function getView(FormView $view, FormView $parent = null, array $errors = [], FormView $form) {
		// Remove circular reference
		$error = array_filter($errors, function ($error) { return $error['id'] == $view->vars['id']; });
		$encoded = [
			'parent' => $parent ? $parent->vars['id'] : null,
			'vars' => array_merge($view->vars, [ 
				'errors' => !empty($error) ? $error : null,
				'form' =>  $form->vars['id'],
			]),
			'children' => array_map(function (FormView $child) use ($view, $errors, $form) {
				return Encoder::getView($child, $view, $errors, $form);
			}, $view->children),
		];

		// Encode prototype and replace key by "composition" for JS keyword
		if (isset($view->vars['prototype'])) {
			$encoded['vars']['composition'] = Encoder::getView($view->vars['prototype'], $view, $errors, $form);
			unset($encoded['vars']['prototype']);
		}

		return $encoded;
	}

	public static function getErrors(string $name, FormErrorIterator $iterator, FormView $parent = null) {
		$errors = [];
		foreach ($iterator as $error) {
			if ($error instanceof FormError) $errors[] = [ 'id' => $name, 'message' => (string) $error->getMessage() ];
			else if ($error instanceof FormErrorIterator) {
				$form = $error->getForm();
				$errors = array_merge($errors, Encoder::getErrors($name.'_'.$form->getName(), $error, $parent));
			}
		}
		return $errors;
	}
}