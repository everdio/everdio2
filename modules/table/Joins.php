<?php

namespace Modules\Table {

    use \Component\Validator;

    final class Joins extends \Component\Validation {
        public function __construct(array $relations, array $joins = []) {
            foreach ($relations as $relation) { 
                if ($relation instanceof Relation && $relation->isValid()) {
                    $joins[] = $relation->execute();
                }
            }
            
            parent::__construct(\implode(false, $joins), [new Validator\NotEmpty]);
        }
    }

}