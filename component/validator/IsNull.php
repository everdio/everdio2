<?php
namespace Component\Validator {
    class IsNull extends \Component\Validator {
        const TYPE = "IS_NULL";
        const MESSAGE = "INVALID_NULL";
        public function execute($value) : bool {
            return (bool) \is_null($value);
        }
    }
}
