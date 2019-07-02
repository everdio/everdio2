<?php
namespace Modules\Table {
    use \Components\Validator;
    final class Find extends \Components\Validation {        
        public function __construct(Select $select, array $operators, string $query = NULL) {            
            foreach ($operators as $thisOperator) {
                if ($thisOperator instanceof Operator) {
                    foreach ($operators as $thatOperator) {
                        if ($thatOperator instanceof Operator && (string) $thisOperator->mapper !== (string) $thatOperator->mapper && (isset($thisOperator->mapper->relations) && in_array((string) $thatOperator->mapper, $thisOperator->mapper->relations))) {
                            $join = new Join2($thatOperator->mapper, $thisOperator->mapper);
                            $query .= $join->execute();
                            if ($thatOperator->mapper->hasMapping()) {
                                $query .= strtoupper($thatOperator->operator) . $thatOperator->execute();
                            }
                        }
                    }
                }
            }

            if ($thatOperator->mapper->hasMapping()) {
                $query .= "WHERE" . $thatOperator->execute();
            }
            
            parent::__construct(sprintf("SELECT%sFROM%s%s", $select->execute(), $thatOperator->mapper->getTable(), $query), [new Validator\IsString]);
        }
    }
}

