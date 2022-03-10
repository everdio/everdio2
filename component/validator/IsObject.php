<?php
namespace Component\Validator {
    class IsObject extends \Component\Validator {
        const TYPE = "IS_OBJECT";
        const MESSAGE = "INVALID_OBJECT";
        public function execute($value) : bool {
            return (bool) \is_object($value);
        }                
    }
}