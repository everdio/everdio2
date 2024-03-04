<?php

namespace Modules\Table {

    final class In extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, string $parameter, array $values) {
            parent::__construct(\sprintf("%sIN('%s')", (\substr($mapper->getField($parameter), 0, 1) == '@' ? \sprintf("%s :", $mapper->getField($parameter)) : (new Column($mapper, $parameter))->execute()), \implode("','", $this->sanitize($values))), [new \Component\Validator\IsString]);
        }
    }

}