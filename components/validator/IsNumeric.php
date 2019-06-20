<?php
namespace Components\Validator {
    class IsNumeric extends \Components\Validator {
        const TYPE = "IS_NUMERIC";
        const MESSAGE = "INVALID_NUMERIC";
        public function execute($value) : bool {
            return (bool) is_numeric($value);
        }
    }
}
