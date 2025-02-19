<?php

namespace Modules\Table {

    use \Component\Validator;

    final class Update extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $set = []) {
            foreach (\array_keys($mapper->restore($mapper->mapping)) as $parameter) {
                $set[] = \sprintf("%s = :%s_%s", $mapper->getField($parameter), $mapper->table, $mapper->getField($parameter));
            }

            parent::__construct(\sprintf("UPDATE %s SET %s", $mapper->resource, \implode(", ", $set)), [new Validator\IsString]);
        }
    }

}