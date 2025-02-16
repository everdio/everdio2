<?php

namespace Modules\Table {

    use \Component\Validator;

    final class Values extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $values = []) {
            foreach ($mapper->restore($mapper->mapping) as $parameter => $value) {
                $values[\sprintf("%s_%s", $mapper->table, $mapper->getField($parameter))] = $value;
            }

            parent::__construct($values, [new Validator\IsArray, new Validator\IsEmpty]);
        }
    }

}