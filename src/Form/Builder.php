<?php

namespace JochLAin\API\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;

use JochLAin\API\Form\Encoder;
use JochLAin\API\Form\Type\APIType;
use JochLAin\API\Mapping\Form;
use JochLAin\API\Parser\FormParser;

class Builder
{
	public static function build(FormFactoryInterface $factory, string $classname, $data = null, string $key = null, string $name = null, int $depth = 0) {
		$options = [ 'data_class' => $classname, 'name' => $name, 'key' => $key, 'depth' => $depth ];
        $builder = $factory->createBuilder(APIType::class, $data, $options);
        $form = $builder->getForm();
		return $form;
	}

	public static function view(FormFactoryInterface $factory, string $classname, mixed $data = null, string $key = null, string $name = null, int $depth = 0) {
		$form = Builder::build($factory, $classname, $data, $key, $name, $depth);

		return Encoder::encode($form);
	}
}