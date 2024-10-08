<?php

namespace Modules\Table {

    final class Column extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $table, string $parameter) {
            parent::__construct(\sprintf("%s.`%s`", $table->resource, $table->getField($parameter)), [new \Component\Validator\NotEmpty]);
        }
    }

}