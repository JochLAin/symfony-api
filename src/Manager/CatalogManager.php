<?php

namespace Jochlain\API\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Jochlain\API\Form\Encoder;
use Jochlain\API\Mapping\Catalog;
use Jochlain\API\Parser\TableParser;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class CatalogManager
{
    protected $em;
    protected $factory;
    protected $request;
    protected $tp;

    public function __construct(EntityManagerInterface $em, FormFactoryInterface $factory, RequestStack $stack, TableParser $tp) {
        $this->em = $em;
        $this->factory = $factory;
        $this->request = $stack->getMasterRequest();
        $this->tp = $tp;
    }

    public function build(string $classname, array $properties = null, array $constraints = [], array $sorts = []) {
        if (!$properties) throw new \Exception(sprintf('No properties set for class %s', $classname));

        $filters = $this->request->get('filters', []);
        $sorts = $this->request->get('sorts', []);
        $table = $this->tp->read($classname);

        $columns = array_map(function ($column) use ($sorts) {
            // Merge request values in column mapping
            if (isset($sorts[$column->getName()])) {
                $column->setSort($sorts[$column->getName()]);
            }
            return $column;
        }, array_filter($table->getColumns(), function ($column) use ($properties) {
            return in_array($column->getName(), $properties);
        }));

        $criterias = array_map(function ($criteria) use ($filters) {
            // Merge request values in criteria mapping
            if (isset($filters[$criteria->getName()])) {
                $criteria->setValue($filters[$criteria->getName()]);
            }
            return $criteria;
        }, array_filter($table->getCriterias(), function ($criteria) use ($properties) {
            return in_array($criteria->getName(), $properties);
        }));

        $metadata = $this->em->getClassMetadata($classname);
        $builder = $this->factory->createBuilder();
        foreach ($criterias as $criteria) {
            $criteria->improve($metadata);
            $builder->add($criteria->getName(), $criteria->getType(), $criteria->getOptions());
        }
        $form = $builder->getForm();

        return new Catalog($classname, $columns, $criterias, $form);
    }
}