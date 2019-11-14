<?php
namespace Modules\Table {
    use \Components\Validator;
    final class Find extends \Components\Validation {
        public function __construct(Select $select, From $from, array $relations= [], array $filters = [], array $joins = [], array $operators = []) {      
            foreach ($relations as $relation) {
                if ($relation instanceof Relation && $relation->isValid()) {
                    $joins[] = $relation->execute();
                } 
            }
            
            foreach ($filters as $filter) {
                if ($filter instanceof Filter && $filter->isValid()) {
                    $operators[] = $filter->execute();
                }
            }
            
            parent::__construct(sprintf("SELECT%sFROM%s%s%s", $select->execute(), $from->execute(), implode(false, $joins), (sizeof($operators) ? sprintf("WHERE%s", implode(false, $operators)) : false)), [new Validator\IsString]);
        }
    }
}

