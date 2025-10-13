<?php

namespace Modules\Table {

    final class Select extends \Component\Validation {

        public function __construct(array $mappers, array $select = []) {
            foreach (\array_reverse($mappers) as $mapper) {
                if ($mapper instanceof \Component\Core\Adapter\Mapper) {
                    foreach ((new Columns($mapper, $mapper->mapping))->execute() as $parameter => $column) {
                        $select[] = \sprintf("%sAS`%s`", $column,  $parameter);
                    }
                }
            }
            

            parent::__construct($select, [new \Component\Validator\IsArray]);
        }
    }

}