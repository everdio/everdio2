<?php
namespace Modules\Table {
    final class Find extends \Component\Validation {
        public function __construct(array $validations = [], string $operator = "AND", string $select = NULL, string $from = NULL, array $relations = [], array $operators = [], string $orderby = NULL, string $query = NULL) {                  
            foreach ($validations as $validation) {
                if ($validation instanceof \Component\Validation && $validation->isValid()) {
                    if ($validation instanceof Select || $validation instanceof Count) {
                        $select = $validation->execute();
                    } elseif ($validation instanceof From) {
                        $from = $validation->execute();
                    } elseif ($validation instanceof Filter) {
                        $operators[] = $validation->execute();
                    } elseif ($validation instanceof Relation) {
                        $relations[] = $validation->execute();
                    } elseif ($validation instanceof Join) {
                        $relations[] = $validation->execute();
                    } elseif ($validation instanceof OrderBy) {
                        $orderby = $validation->execute();
                    } else { 
                        $query .= $validation->execute();
                    }
                }
            }
            
            parent::__construct($select . $from . \implode(false, $relations) . (\sizeof($operators) ? \sprintf("WHERE%s", \implode($operator, $operators)) : false) . $orderby . $query, [new \Component\Validator\IsString\Contains(["SELECT", "FROM"])]);
        }
    }
}

