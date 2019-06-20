<?php
namespace Components\Validator {
    class IsEmpty extends \Components\Validator {
        const TYPE = "IS_EMPTY";
        const MESSAGE = "NOT_EMPTY";
        public function execute($value) : bool {
            return (bool) $value === NULL || empty($value);            
        }
    }
}