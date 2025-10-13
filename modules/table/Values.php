<?php
namespace Modules\Table {

    use \Component\Validator;

    final class Values extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $parameters, array $values = []) {
            foreach ((new Bind($mapper, $parameters))->execute() as $parameter => $value) {
                $values[$mapper->label . $parameter] = $value;
            }
            
            parent::__construct($values, [new Validator\IsArray]);
        }
    }

}