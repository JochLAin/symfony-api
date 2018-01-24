<?php

namespace Jochlain\API\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

use Jochlain\API\Mapping\Form;
use Jochlain\API\Parser\FormParser;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class FormType extends AbstractType
{
	protected $parser;

	public function __construct(FormParser $parser) {
		$this->parser = $parser;
	}

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$key = $options['key'];
		if ($options['mapping'] instanceof Form) {
			return Form::buildForm($builder, $options['mapping'], $key);
		}
		$form = $this->parser->read($options['data_class'], $options['name'], $options['depth']);
		unset($options['mapping'], $options['depth'], $options['key'], $options['name']);
		$form->mergeOptions($options);
		Form::buildForm($builder, $form, $key);
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setRequired('data_class');
		$resolver->setDefaults([
			'mapping' => null,
			'depth' => 0,
			'key' => null,
			'name' => null,
		]);
	}
}