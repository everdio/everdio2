<?php

namespace Modules\Table {

    use \Component\Validator,
        \Component\Core\Adapter\Mapper;    

    final class Join extends \Component\Validation {
        public function __construct(Mapper $thatMapper, Mapper $thisMapper, array $keys, string $join = "join", string $operator = "and", array $operators = []) {
            foreach ($keys as $thatKey => $thisKey) {
                if ($thatMapper->exists($thisKey) && $thisMapper->exists($thatKey)) {
                    $operators[] = sprintf("`%s`.`%s`.`%s`=`%s`.`%s`.`%s`", $thatMapper->database, $thatMapper->table, $thatMapper->getField($thisKey), $thisMapper->database, $thisMapper->table, $thisMapper->getField($thatKey));
                }
            }
            
            parent::__construct(sprintf("%s`%s`.`%s`ON%s%s", \strtoupper($join), $thatMapper->database, $thatMapper->table, \implode(\strtoupper($operator), $operators), (($filter = new Filter([$thatMapper], \strtoupper($operator)))->isValid() ? \strtoupper($operator) . $filter->execute() : false)), [new Validator\NotEmpty]);
        }
    }

}