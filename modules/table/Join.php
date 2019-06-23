<?php 
namespace Modules\Table {
    use \Components\Validator;
    class Join extends \Components\Validation {
        public function __construct(\Modules\Table $thatTable, \Modules\Table $thisTable, string $join = NULL, string $operator = "AND", array $values = []) {
            if (isset($thisTable->relations) && array_search(get_class($thatTable), $thisTable->relations)) {                
                $values[] = sprintf("%s JOIN`%s`.`%s`ON%s=%s", $join, $thatTable->database, $thatTable->table, $thisTable->getColumn(array_search(get_class($thatTable), $thisTable->relations)), $thatTable->getColumn(array_search(get_class($thatTable), $thisTable->relations)));
            } elseif (isset($thatTable->relations) && array_search(get_class($thisTable), $thatTable->relations)) {
                $values[] = sprintf("%s JOIN`%s`.`%s`ON%s=%s", $join, $thatTable->database, $thatTable->table, $thisTable->getColumn(array_search(get_class($thisTable), $thatTable->relations)), $thatTable->getColumn(array_search(get_class($thisTable), $thatTable->relations)));
            } else {
                throw new Event("invalid relation");
            }    
            
            if (sizeof(array_filter($thatTable->restore($thatTable->mapping)))) {
                $and = new Operator($thatTable, $operator);
                $values[] = $operator . $and->execute();
            }
            
            parent::__construct(implode(PHP_EOL, $values), array(new Validator\IsString));
        }
    }
}