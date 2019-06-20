<?php
namespace Components\Validator {
    class IsArray extends \Components\Validator {
        const TYPE = "IS_ARRAY";
        const MESSAGE = "INVALID_ARRAY";
        
        public function execute($value) : bool {
            return (bool) is_array($value);
        }
    }
}
