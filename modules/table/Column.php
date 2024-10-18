<?php

namespace Modules\Table {

    final class Column extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, string $parameter) {
            parent::__construct((\substr($mapper->getField($parameter), 0, 1) == '@' ? \sprintf("%s :", $mapper->getField($parameter)) : \sprintf("%s.%s", $mapper->resource, $mapper->getField($parameter))), [new \Component\Validator\NotEmpty]);
        }
    }

}