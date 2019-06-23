<?php
namespace Components\Validator {
    class IsNull extends \Components\Validator {
        const TYPE = "IS_NULL";
        const MESSAGE = "INVALID_NULL";
        public function execute($value) : bool {
            return (bool) is_null($value);
        }
    }
}
