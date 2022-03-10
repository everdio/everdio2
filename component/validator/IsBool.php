<?php
namespace Component\Validator {
    class IsBool extends \Component\Validator {
        const TYPE = "IS_BOOL";
        const MESSAGE = "INVALID_BOOLEAN";
        public function execute($value) : bool {
            return (bool) \is_bool($value);
        }
    }
}
