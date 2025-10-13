<?php
namespace Modules\Table {

    use \Component\Validator;

    final class Columns extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $parameters, array $columns = []) {
            foreach ($mapper->inter($parameters) as $parameter) {
                $columns[$parameter] = (\substr($mapper->getField($parameter), 0, 1) == "@" ? \sprintf("`%s` :", $mapper->getField($parameter)) : \sprintf("%s.`%s`", $mapper->resource, $mapper->getField($parameter)));
            }

            parent::__construct($columns, [new Validator\IsArray]);
        }
    }

}