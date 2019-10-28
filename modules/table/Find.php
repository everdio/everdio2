<?php
namespace Modules\Table {
    use \Components\Validator;
    final class Find extends \Components\Validation {
        public function __construct(Select $select, From $from, array $tables, string $join = null, string $operator = "and", string $expression = "=", string $query = NULL) {      
            foreach ($tables as $thisTable) {
                if ($thisTable instanceof \Modules\Table) {
                    foreach ($tables as $thatTable) {
                        if ($thatTable instanceof \Modules\Table && $thisTable !== $thatTable) {
                            $relation = new Relation($thisTable, $thatTable, $join);
                            if ($relation->isValid()) {
                                $query .= $relation->execute();
                                $filter = new Filter($thatTable);
                                if ($filter->isValid()) {
                                    $query .= $operator . $filter->execute();
                                }
                            }
                        }
                    }
                }
            }
            
            $filter = new Filter($thatTable, $operator, $expression);
            if ($filter->isValid()) {
                $query .= "WHERE" . $filter->execute();
            }
            
            parent::__construct(sprintf("SELECT%sFROM%s%s", $select->execute(), $from->execute(), $query), [new Validator\IsString]);
        }
    }
}

