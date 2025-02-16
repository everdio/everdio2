<?php

namespace Modules\Table {

    use \Component\Validator;

    final class Insert extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $insert = []) {
            foreach (\array_keys($mapper->restore($mapper->mapping)) as $parameter) {
                $insert[$mapper->getField($parameter)] = \sprintf(":%s_%s", $mapper->table, $mapper->getField($parameter));
            }

            parent::__construct(\sprintf("INSERT OR IGNORE INTO %s (%s) VALUES (%s)", $mapper->resource, \implode(", ", \array_keys($insert)), \implode(", ", $insert)), [new Validator\IsString]);
        }
    }

}