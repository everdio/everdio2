<?php 
namespace Modules\Table {
    use \Component\Validator, \Component\Core\Adapter\Mapper;
    final class Relation extends \Component\Validation {
        public function __construct(Mapper $onTable, array $tables, string $join = "", string $operator = "AND", array $joins = []) {
            foreach ($tables as $joinTable) {   
                if ($joinTable instanceof Mapper && !$onTable instanceof $joinTable && isset($onTable->keys) && isset($joinTable->primary) && !\array_key_exists((string) $joinTable, $joins)) {
                    foreach ($onTable->keys as $onKey => $joinKey) {
                        if (isset($joinTable->primary) && \in_array($joinKey, $joinTable->primary) && !\array_key_exists((string) $joinTable, $joins)) {
                            $filter = new Filter([$joinTable], $operator);
                            $joins[(string) $joinTable] = \sprintf("%s JOIN`%s`.`%s`ON`%s`.`%s`.`%s`=`%s`.`%s`.`%s`", \strtoupper((!$join && isset($onTable->get($onKey)->IS_EMPTY) ? "LEFT" : $join)), $joinTable->database, $joinTable->table, $joinTable->database, $joinTable->table, $joinTable->getField($onTable->keys[$onKey]), $onTable->database, $onTable->table, $onTable->getField($onKey)) . ($filter->isValid() ? \strtoupper($operator) . $filter->execute() : false);
                        }
                    }
                }
            }
      
            parent::__construct(\implode(\PHP_EOL, $joins), [new Validator\IsEmpty, new Validator\IsString\Contains(["JOIN"])]);
        }        
    }
}