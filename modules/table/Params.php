<?php
namespace Modules\Table {

    use \Component\Validator;

    final class Params extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $parameters, array $values = []) {
            foreach (\array_keys((new Bind($mapper, $parameters))->execute()) as $parameter) {
                $values[$parameter] = \sprintf(":%s", $mapper->label . $parameter);
            }

            parent::__construct($values, [new Validator\IsArray]);
        }
    }

}