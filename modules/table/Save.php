<?php

namespace Modules\Table {

    final class Save extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper) {
            parent::__construct(\sprintf("INSERT INTO %s(%s)VALUES(%s)", $mapper->resource, (new Insert($mapper))->execute(), (new Values($mapper))->execute()), [new \Component\Validator\IsString]);
        }
    }

}

