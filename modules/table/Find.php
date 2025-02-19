<?php

namespace Modules\Table {

    final class Find extends \Component\Validation {

        public function __construct(array $validations = [], string $operator = "AND", array $select = [], ?string $from = NULL, array $joins = [], array $operators = [], ?string $orderby = NULL, ?string $groupby = NULL) {
            foreach ($validations as $validation) {
                if ($validation instanceof \Component\Validation && $validation->isValid()) {
                    if ($validation instanceof Select || $validation instanceof Count) {
                        $select[] = $validation->execute();
                    } elseif ($validation instanceof From) {
                        $from = $validation->execute();
                    } elseif ($validation instanceof Filter || $validation instanceof In) {
                        $operators[] = $validation->execute();
                    } elseif ($validation instanceof Joins) {
                        $joins[] = $validation->execute();                        
                    } elseif ($validation instanceof GroupBy) {
                        $groupby = $validation->execute();                              
                    } elseif ($validation instanceof OrderBy) {
                        $orderby = $validation->execute();                  
                    }
                }
            }            
            
            parent::__construct(\trim(\sprintf("SELECT %s", \implode(" ", \array_filter([\implode(", ", $select), $from, \implode(" ", \array_reverse($joins)), (new Operators($operators, $operator))->execute(), $groupby, $orderby])))), [new \Component\Validator\IsString\Contains(["FROM"])]);
        }
    }

}

