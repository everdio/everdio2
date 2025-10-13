<?php

namespace Modules\Table {

    final class OrderBy extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $orderby, array $values = []) {
            foreach ($orderby as $order => $parameters) {
                foreach ($mapper->inter($parameters) as $parameter) {
                    $values[] = \sprintf("%s.`%s` %s", $mapper->resource, $mapper->getField($parameter), \strtoupper($order));
                }
            }

            parent::__construct(\trim(\sprintf("ORDER BY %s", \implode(", ", $values))), [new \Component\Validator\IsString\Contains(["DESC", "ASC"])], self::STRICT);
        }
    }

}