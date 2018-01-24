<?php

namespace Jochlain\API\Parser;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Jochlain\API\Annotation\Form as FormAnnotation;
use Jochlain\API\Annotation\Forms as FormsAnnotation;
use Jochlain\API\Annotation\Field as FieldAnnotation;
use Jochlain\API\Annotation\Fields as FieldsAnnotation;
use Jochlain\API\Form\TypeType;
use Jochlain\API\Mapping\Form;
use Jochlain\API\Mapping\Field;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class FormParser 
{
    const MAX_DEPTH = 1;

    private $em;
    private $user;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $storage) {
        $this->em = $em;
        $this->user = $storage->getToken() ? $storage->getToken()->getUser() : null;
        $this->user = is_string($this->user) ? null : $this->user;
    }

    // Parse annotations (more permissive with the target than parse)
    public function read($target, string $name = null, $depth = 0) {
        if ($target instanceof ClassMetadata) return $this->parse($target, $name, $depth);
        else if ($target instanceof Entity && $target->metadata) return $target;
        else if ($target instanceof Entity) return $this->read($target->name, $name, $depth);
        else if (is_string($target) && class_exists($target)) return $this->parse($this->em->getClassMetadata($target), $name, $depth);
        else if (is_object($target)) return $this->parse($this->em->getClassMetadata(get_class($target)), $name, $depth);
        else if (is_string($target)) {
            $names = array_map(function (ClassMetadata $metadata) {
                return $metadata->getTableName();
            }, $this->em->getMetadataFactory()->getAllMetadata());
            if (in_array($target, $names)) return $this->parse($metadata, $name, $depth);
        }
    }

    // Parse annotations to get the Form mapping
    public function parse(ClassMetadata $metadata, string $name = null, $depth = 0) {
    	if (!$this->match($metadata, !$name)) return null;

        $reader = new AnnotationReader;
        $reflection = $metadata->getReflectionClass();

        $forms = [];
        // Retrieve all FormAnnotation from entity metadata 
        if ($annotation = $reader->getClassAnnotation($reflection, FormAnnotation::class)) $forms[] = $annotation;
        if ($annotation = $reader->getClassAnnotation($reflection, FormsAnnotation::class)) {
            $forms = array_merge($forms, $annotation->getForms());
        }

        $annotation = null;
        // Get the specific or default FormAnnotation if is specified
        foreach ($forms as $form) {
            if ($form->getName() == $name || (!$name && $form->getName() == 'default')) {
                $annotation = $form;
            }
        }

        $form = new Form($metadata, $depth);
        if ($annotation) {
            // Merge information from annotation into mapping and add all fields specified inside
            $form->mergeAnnotation($annotation);
            foreach ($annotation->getFields() as $field) $this->addField($form, $name, $field);
        }

        // Add all fields annotate on properties whose have the form corresponding
        foreach ($metadata->getReflectionProperties() as $name => $reflection) {
            if ($field = $reader->getPropertyAnnotation($reflection, FieldAnnotation::class)) $this->addFieldFor($form, $name, $field);
            if ($fields = $reader->getPropertyAnnotation($reflection, FieldsAnnotation::class)) {
                foreach ($fields as $field) $this->addFieldFor($form, $name, $field);
            }
        }

        return $form;
    }

    // Add field to form
    public function addField(Form $form, string $name, FieldAnnotation $annotation) {
        $field = new Field($form, $this->user);
        $field->mergeAnnotation($name, $annotation);

        // If the field is an automatic FormType or collection of FormType, limits the depth to MAX_DEPTH
        if ((($field->getType() == CollectionType::class && $field->getOptions()['entry_type'] == FormType::class) || $field->getType() == FormType::class) && $form->getDepth() >= FormParser::MAX_DEPTH) return;

        $form->addField($field);
    }

    // Add field to form only if field specify is in form 
    public function addFieldFor(Form $form, string $name, FieldAnnotation $field) {
        if ($form->getName() != 'default' && !in_array($form->getName(), $field->getForms())) return;
        $this->addField($form, $name, $field);
    }

    // Say if entity metadata contains annotations
    public function match(ClassMetadata $metadata, bool $default = true)
    {
        $reader = new AnnotationReader;
        $reflection = $metadata->getReflectionClass();
        if ($reader->getClassAnnotation($reflection, FormAnnotation::class)) return true;
        if ($reader->getClassAnnotation($reflection, FormsAnnotation::class)) return true;
        if ($default) {
            foreach ($metadata->getReflectionProperties() as $reflection) {
                if ($reader->getPropertyAnnotation($reflection, FieldAnnotation::class)) return true;
                if ($reader->getPropertyAnnotation($reflection, FieldsAnnotation::class)) return true;
            }
        }
        return false;
    }
}