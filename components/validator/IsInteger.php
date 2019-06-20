<?php
namespace Components\Validator {
    class IsInteger extends \Components\Validator {
        const TYPE = "IS_INT";
        const MESSAGE = "INVALID_INTEGER";
        public function execute($value) : bool {
            return (bool) is_int($value);
        }
    }
}
