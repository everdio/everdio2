<?php

namespace Modules\Table {

    final class Tables extends \Component\Validation {

        public function __construct(array $mappers, array $columns = []) {
            foreach ($mappers as $mapper) {
                if ($mapper instanceof \Component\Core\Adapter\Mapper && isset($mapper->mapping)) {
                    foreach ($mapper->inter($mapper->mapping) as $parameter) {
                        $columns[$parameter] = \sprintf(" %s AS`%s`", (\substr($mapper->getField($parameter), 0, 1) === '@' ? $mapper->getField($parameter) : \sprintf("`%s`.`%s`.`%s`", $mapper->database, $mapper->table, $mapper->getField($parameter))), $parameter);
                    }
                }
            }
            
            parent::__construct(\implode(",", $columns), [new \Component\Validator\IsString]);
        }
    }

}