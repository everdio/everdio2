<?php
namespace Modules\Table {
    use \Components\Validator;
    final class Find extends \Components\Validation {        
        public function __construct(Select $select, From $from, array $operators, string $query = NULL) {            
            foreach ($operators as $thisOperator) {
                if ($thisOperator instanceof Operator) {
                    foreach ($operators as $thatOperator) {
                        if ($thatOperator instanceof Operator && $thisOperator->mapper !== $thatOperator->mapper) {
                            $join = new Join($thatOperator->mapper, $thisOperator->mapper);
                            $query .= $join->execute();
                            if (!in_array(false, $thatOperator->validate())) {
                                $query .= strtoupper($thatOperator->operator) . $thatOperator->execute();
                            }
                        }
                    }
                }
            }
            
            if (!in_array(false, $thatOperator->validate())) {
                $query .= "WHERE" . $thatOperator->execute();
            }
            
            parent::__construct(sprintf("SELECT%sFROM%s%s", $select->execute(), $from->execute(), $query), [new Validator\IsString]);
        }
    }
}

