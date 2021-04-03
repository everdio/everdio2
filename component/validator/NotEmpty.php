<?php
namespace Component\Validator {
    class NotEmpty extends \Component\Validator {
        const TYPE = "NOT_EMPTY";
        const MESSAGE = "IS_EMPTY";
        public function execute($value) : bool {
            return (bool) !empty($value);
        }
    }
}
