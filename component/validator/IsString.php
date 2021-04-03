<?php
namespace Component\Validator {
    class IsString extends \Component\Validator {
        const TYPE = "IS_STRING";
        const MESSAGE = "INVALID_STRING";
        public function execute($value) : bool {
            return (bool) is_string($value) && $value !== "";
        }
    }
}
