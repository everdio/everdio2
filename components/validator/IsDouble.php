<?php
namespace Components\Validator {
    class IsDouble extends \Components\Validator {
        const TYPE = "IS_DOUBLE";
        const MESSAGE = "INVALID_DOUBLE";
        public function execute($value) : bool {
            return (bool) is_double($value);
        }
    }
}
