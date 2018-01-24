<?php

namespace Jochlain\API\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

use Jochlain\API\Form\Encoder;
use Jochlain\API\Form\Type\FormType;
use Jochlain\API\Mapping\Form;
use Jochlain\API\Parser\FormParser;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Builder
{
	protected $factory;

	public function __construct(FormFactoryInterface $factory) {
		$this->factory = $factory;
	}

	public function build(string $classname, $data = null, string $key = null, string $name = null, int $depth = 0) {
		return Builder::builds($this->factory, $classname, $data, $key, $name, $depth);
	}

	public function view(string $classname, mixed $data = null, string $key = null, string $name = null, int $depth = 0) {
		return Builder::views($this->factory, $classname, $data, $key, $name, $depth);
	}

	public static function builds(FormFactoryInterface $factory, string $classname, $data = null, string $key = null, string $name = null, int $depth = 0) {
		$options = [ 'data_class' => $classname, 'name' => $name, 'key' => $key, 'depth' => $depth ];
        $builder = $factory->createBuilder(FormType::class, $data, $options);
        $form = $builder->getForm();
		return $form;
	}

	public static function views(FormFactoryInterface $factory, string $classname, mixed $data = null, string $key = null, string $name = null, int $depth = 0) {
		$form = Builder::builds($factory, $classname, $data, $key, $name, $depth);
		return Encoder::encodes($form);
	}
}