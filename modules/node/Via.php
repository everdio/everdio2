<?php

namespace Modules\Node {

    final class Via extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $conditions = [], string $operator = "and", string $expression = "=", string $match = "text()") {
            parent::__construct((new Filter($mapper->path, \array_merge($conditions, [new Condition($mapper, $operator, $expression, $match)]), $operator))->execute(), [new \Component\Validator\IsString]);
        }
    }

}