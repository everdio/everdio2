<?php

namespace Modules\Table {

    use \Component\Validator;

    final class Save extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper) {
            switch ($mapper->getAttribute(\PDO::ATTR_DRIVER_NAME)) {
                case "mysql":
                    parent::__construct(\sprintf("INSERT %s ON DUPLICATE KEY UPDATE %s", (new Into($mapper))->execute(), \implode(", ", (new Conditions($mapper, $mapper->mapping))->execute())), [new Validator\IsString\Contains([":"])]);
                    break;
                case "sqlite":
                    parent::__construct(\sprintf("INSERT %s ON CONFLICT(%s) DO UPDATE SET %s", (new Into($mapper))->execute(), \implode(", ", (new Columns($mapper, $mapper->primary))->execute()), \implode(", ", (new Set($mapper, $mapper->mapping))->execute())), [new Validator\IsString\Contains([":"])]);
                    break;
                default:
                    throw \RuntimeException("UNKNOWN_PDO_DRIVER %s", $mapper->getAttribute(\PDO::ATTR_DRIVER_NAME));
            }
        }
    }
}