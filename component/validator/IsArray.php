<?php
namespace Component\Validator {
    class IsArray extends \Component\Validator {
        const TYPE = "IS_ARRAY";
        const MESSAGE = "INVALID_ARRAY";
        
        public function execute($value) : bool {
            return (bool) \is_array($value);
        }
    }
}
