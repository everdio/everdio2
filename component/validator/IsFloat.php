<?php
namespace Component\Validator {
    class IsFloat extends \Component\Validator {
        const TYPE = "IS_FLOAT";
        const MESSAGE = "INVALID_FLOAT";
        public function execute($value) : bool {
            return (bool) is_float($value);
        }
    }
}
