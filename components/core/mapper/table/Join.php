<?php 
namespace Components\Core\Mapper\Table {
    use \Components\Validator;
    class Join extends \Components\Validation {
        public function __construct(\Components\Core\Mapper\Table $thatMapper, \Components\Core\Mapper\Table $thisMapper, string $join = NULL, string $operator = "AND", array $values = []) {
            if (isset($thisMapper->relations) && array_search(get_class($thatMapper), $thisMapper->relations)) {                
                $values[] = sprintf("%s JOIN`%s`.`%s`ON%s=%s", $join, $thatMapper->database, $thatMapper->table, $thisMapper->getColumn(array_search(get_class($thatMapper), $thisMapper->relations)), $thatMapper->getColumn(array_search(get_class($thatMapper), $thisMapper->relations)));
            } elseif (isset($thatMapper->relations) && array_search(get_class($thisMapper), $thatMapper->relations)) {
                $values[] = sprintf("%s JOIN`%s`.`%s`ON%s=%s", $join, $thatMapper->database, $thatMapper->table, $thisMapper->getColumn(array_search(get_class($thisMapper), $thatMapper->relations)), $thatMapper->getColumn(array_search(get_class($thisMapper), $thatMapper->relations)));
            } else {
                throw new Event("invalid relation");
            }    
            
            if ($thatMapper->isMapped()) {
                $and = new Operator($thatMapper, $operator);
                $values[] = $operator . $and->execute();
            }
            
            parent::__construct(implode(PHP_EOL, $values), array(new Validator\IsString));
        }
    }
}