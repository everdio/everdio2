<?php
namespace Components\Validator {
    class IsFloat extends \Components\Validator {
        const TYPE = "IS_FLOAT";
        const MESSAGE = "INVALID_FLOAT";
        public function execute($value) : bool {
            return (bool) is_float($value);
        }
    }
}
