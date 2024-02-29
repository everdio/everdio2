<?php

namespace Modules\Table {

    use \Component\Validator,
        \Component\Core\Adapter\Mapper;    

    final class Join extends \Component\Validation {
        public function __construct(Mapper $thatMapper, Mapper $thisMapper, array $keys, string $join = "join", string $operator = "and", array $operators = []) {
            foreach ($keys as $thatKey => $thisKey) {                                                
                if ($thatMapper->exists($thatKey) && $thisMapper->exists($thisKey)) {
                    $join = (isset($thisMapper->getParameter($thisKey)->IS_EMPTY) ? "left join" : $join);
                    $operators[] = sprintf("`%s`.`%s`.`%s`=`%s`.`%s`.`%s`", $thatMapper->database, $thatMapper->table, $thatMapper->getField($thatKey), $thisMapper->database, $thisMapper->table, $thisMapper->getField($thisKey));
                }
            }
            
            
            
            parent::__construct(sprintf("%s`%s`.`%s`ON%s%s", \strtoupper($join), $thatMapper->database, $thatMapper->table, \implode(\strtoupper($operator), $operators), (($filter = new Filter([$thatMapper], \strtoupper($operator)))->isValid() ? \strtoupper($operator) . $filter->execute() : false)), [new Validator\NotEmpty]);
        }
    }

}