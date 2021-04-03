<?php
namespace Component\Validator {
    class IsDefault extends \Component\Validator {
        const TYPE = "IS_DEFAULT";
        const MESSAGE = "INVALID_DEFAULT";
        public function execute($value) : bool {
            return (bool) (!empty($value) || $value === 0);
        }
    }
}
