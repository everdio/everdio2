<?php

namespace Modules\Table {

    use \Component\Validator;

    class Bind extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $parameters, array $bind = []) {
            foreach ($mapper->restore($parameters) as $parameter => $value) {
                if (!empty($value) || !\is_bool($value)) {                
                    $bind[$parameter] = $value;
                }
            }
   
            parent::__construct($bind, [new Validator\IsArray]);
        }
    }

}