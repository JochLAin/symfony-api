<?php

namespace Jochlain\API\Mapping;

use Symfony\Component\Form\Form;

use Jochlain\API\Form\Encoder;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class Catalog
{
    protected $classname;
    protected $columns;
    protected $criterias;
    protected $form;

    public function __construct(string $classname, array $columns, array $criterias, Form $form) {
        $this->classname = $classname;
        $this->columns = $columns;
        $this->criterias = $criterias;
        $this->form = $form;
    }

    public function getColumns() {
        return array_values(array_map(function ($column) {
            $property = [
                'name'      => $column->getName(),
                'label'     => $column->getLabel(),
                'visible'   => $column->isVisible(),
                'sort'      => $column->getSort(),
            ];

            if ($this->form->has($column->getName())) {
                $field = $this->form->get($column->getName());
                $property = array_merge($property, [
                    'field' => Encoder::encodes($field),
                    'selected' => !is_null($field->getData()) && $field->getData() !== '',
                ]);
            }

            return $property;
        }, $this->columns));;
    }

    public function getCriterias() {
        $filters = [];
        foreach ($this->criterias as $criteria) {
            $field = $this->form->get($criteria->getName());
            if (is_null($field->getData()) || $field->getData() === '') continue;
            $filters[$criteria->getName()] = $field->getData();
        }
        return $filters;
    }

    public function getSorts() {
        $sorts = [];
        foreach ($this->columns as $column) {
            if (!$column->getSort()) continue;
            $sorts[$column->getName()] = $column->getSort();
        }
        return $sorts;
    }
}