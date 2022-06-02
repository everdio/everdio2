<?php 
namespace Modules\Table {
    use \Component\Validator, \Component\Core\Adapter\Mapper;
    final class Join extends \Component\Validation {
        public function __construct(Mapper $thatMapper, Mapper $thisMapper, array $keys, string $join = "JOIN", string $operator = "AND", array $operators = []) {
            foreach ($keys as $thatKey => $thisKey) {
                $operators[] = sprintf("`%s`.`%s`.`%s`=`%s`.`%s`.`%s`", $thatMapper->database, $thatMapper->table, $thatMapper->getField($thatKey), $thisMapper->database, $thisMapper->table, $thisMapper->getField($thisKey));
            }
            
            $filter = new Filter([$thatMapper], $operator);
            parent::__construct(sprintf("%s`%s`.`%s`ON%s%s", \strtoupper($join), $thatMapper->database, $thatMapper->table, \implode(\strtoupper($operator), $operators), ($filter->isValid() ? \strtoupper($operator) . $filter->execute() : false)), [new Validator\NotEmpty]);
        }        
    }
}