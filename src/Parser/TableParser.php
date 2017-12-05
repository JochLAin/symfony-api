<?php 

namespace JochLAin\API\Parser;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use JochLAin\API\Annotation\Table as TableAnnotation;
use JochLAin\API\Annotation\Tables as TablesAnnotation;
use JochLAin\API\Annotation\Column as ColumnAnnotation;
use JochLAin\API\Annotation\Columns as ColumnsAnnotation;
use JochLAin\API\Annotation\Criteria as CriteriaAnnotation;
use JochLAin\API\Annotation\Criterias as CriteriasAnnotation;
use JochLAin\API\Mapping\Table;
use JochLAin\API\Mapping\Column;
use JochLAin\API\Mapping\Criteria;

class TableParser 
{
    const MAX_DEPTH = 1;

    private $em;
    private $user;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $storage) {
        $this->em = $em;
        $this->user = $storage->getToken() ? $storage->getToken()->getUser() : null;
        $this->user = is_string($this->user) ? null : $this->user;
    }

    // Parse annotations (more permissive with the target than TableParser::parse)
    public static function read($target, string $name = null, $depth = 0) {
        if ($target instanceof ClassMetadata) return TableParser::parse($target, $name, $depth);
        else if ($target instanceof Entity && $target->metadata) return $target;
        else if ($target instanceof Entity) return TableParser::read($target->name, $name, $depth);
        else if (is_string($target) && class_exists($target)) return TableParser::parse($this->em->getClassMetadata($target), $name, $depth);
        else if (is_object($target)) return TableParser::parse($this->em->getClassMetadata(get_class($target)), $name, $depth);
        else if (is_string($target)) {
            $names = array_map(function (ClassMetadata $metadata) {
                return $metadata->getTableName();
            }, $this->em->getMetadataFactory()->getAllMetadata());
            if (in_array($target, $names)) return TableParser::parse($metadata, $name, $depth);
        }
    }

    // Parse annotations to get the Table mapping
    public static function parse(ClassMetadata $metadata, string $name = null, $depth = 0) {
    	if (!TableParser::match($metadata, !$name)) return null;

        $reader = new AnnotationReader;
        $reflection = $metadata->getReflectionClass();

        $tables = [];
        // Retrieve all TableAnnotation from entity metadata 
        if ($annotation = $reader->getClassAnnotation($reflection, TableAnnotation::class)) $tables[] = $annotation;
        if ($annotation = $reader->getClassAnnotation($reflection, TablesAnnotation::class)) {
            $tables = array_merge($tables, $annotation->getTables());
        }

        $annotation = null;
        // Get the specific or default TableAnnotation if is specified
        foreach ($tables as $table) {
            if ($table->getName() == $name || (!$name && $table->getName() == 'default')) {
                $annotation = $table;
            }
        }

        $table = new Table($metadata, $depth);
        if ($annotation) {
            // Merge information from annotation into mapping and add all fields specified inside
            $table->mergeAnnotation($annotation);
            foreach ($annotation->getColumns() as $column) TableParser::addColumn($table, $name, $column);
            foreach ($annotation->getCriterias() as $criteria) TableParser::addCriteria($table, $name, $criteria);
        }

        // Add all columns annotate on properties whose have the table corresponding
        foreach ($metadata->getReflectionProperties() as $name => $reflection) {
            if ($column = $reader->getPropertyAnnotation($reflection, ColumnAnnotation::class)) TableParser::addColumnFor($table, $name, $column);
            if ($columns = $reader->getPropertyAnnotation($reflection, ColumnsAnnotation::class)) {
                foreach ($columns as $column) TableParser::addColumnFor($table, $name, $column);
            }
            if ($criteria = $reader->getPropertyAnnotation($reflection, CriteriaAnnotation::class)) TableParser::addCriteriaFor($table, $name, $criteria);
            if ($criterias = $reader->getPropertyAnnotation($reflection, CriteriasAnnotation::class)) {
                foreach ($criterias as $criteria) TableParser::addCriteriaFor($table, $name, $criteria);
            }
        }

        return $table;
    }

    // Add column to form
    public static function addColumn(Table $table, string $name, ColumnAnnotation $annotation) {
        $column = new Column($table);
        $column->mergeAnnotation($name, $annotation);
        $table->addColumn($column);
    }

    // Add column to form only if column specify is in form 
    public static function addColumnFor(Table $table, string $name, ColumnAnnotation $column) {
        if ($table->getName() != 'default' && !in_array($table->getName(), $column->getTables())) return;
        TableParser::addColumn($table, $name, $column);
    }

    // Add criteria to form
    public static function addCriteria(Table $table, string $name, CriteriaAnnotation $annotation) {
        $criteria = new Criteria($table);
        $criteria->mergeAnnotation($name, $annotation);
        $table->addCriteria($criteria);
    }

    // Add criteria to form only if criteria specify is in form 
    public static function addCriteriaFor(Table $table, string $name, CriteriaAnnotation $criteria) {
        if ($table->getName() != 'default' && !in_array($table->getName(), $criteria->getTables())) return;
        TableParser::addCriteria($table, $name, $criteria);
    }

    // Say if entity metadata contains annotations
    public static function match(ClassMetadata $metadata, bool $default = true)
    {
        $reader = new AnnotationReader;
        $reflection = $metadata->getReflectionClass();
        if ($reader->getClassAnnotation($reflection, TableAnnotation::class)) return true;
        if ($reader->getClassAnnotation($reflection, TablesAnnotation::class)) return true;
        if ($default) {
            foreach ($metadata->getReflectionProperties() as $reflection) {
                if ($reader->getPropertyAnnotation($reflection, ColumnAnnotation::class)) return true;
                if ($reader->getPropertyAnnotation($reflection, ColumnsAnnotation::class)) return true;
                if ($reader->getPropertyAnnotation($reflection, CriteriaAnnotation::class)) return true;
                if ($reader->getPropertyAnnotation($reflection, CriteriasAnnotation::class)) return true;
            }
        }
        return false;
    }
}