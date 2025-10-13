<?php

namespace Modules\Table {

    final class GroupBy extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $parameters) {
            parent::__construct(\sprintf("GROUP BY `%s`", (new Columns($mapper, $parameters))->execute()), [new \Component\Validator\IsString\Contains([","])], self::STRICT);
        }
    }

}