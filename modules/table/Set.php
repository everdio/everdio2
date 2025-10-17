<?php
namespace Modules\Table {

    use \Component\Validator;

    final class Set extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $parameters, string $expression = "=", array $conditions = []) {
            foreach ((new Params($mapper, $parameters))->execute() as $parameter => $param) {
                $conditions[$parameter] = \sprintf("`%s` %s %s", $mapper->getField($parameter), $expression, $param);
            }

            parent::__construct($conditions, [new Validator\IsArray]);
        }
    }

}