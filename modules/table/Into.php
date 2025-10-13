<?php

namespace Modules\Table {

    use \Component\Validator;

    final class Into extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $columns = []) {
            $params = (new Params($mapper, $mapper->mapping))->execute();
            
            foreach (\array_keys($params) as $parameter) {
                $columns[] = \sprintf("`%s`", $mapper->getField($parameter));
            }
            
            parent::__construct(\sprintf("INTO %s (%s) VALUES (%s)", $mapper->resource, \implode(", ", $columns), \implode(", ", $params)), [new Validator\NotEmpty]);
        }
    }

}