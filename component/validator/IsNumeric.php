<?php
namespace Component\Validator {
    class IsNumeric extends \Component\Validator {
        const TYPE = "IS_NUMERIC";
        const MESSAGE = "INVALID_NUMERIC";
        public function execute($value) : bool {
            return (bool) \is_numeric($value);
        }
    }
}
