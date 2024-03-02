<?php

namespace Modules\Table {

    final class GroupBy extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper) {
            parent::__construct(\sprintf("GROUP BY`%s`", \implode("`, `", \array_flip(\array_intersect($mapper->primary, $mapper->mapping)))), [new \Component\Validator\IsString\Contains(["GROUP BY"])], self::STRICT);
        }
    }

}