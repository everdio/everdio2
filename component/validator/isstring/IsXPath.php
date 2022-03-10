<?php
namespace Component\Validator\IsString {
    class IsXPath extends \Component\Validator\IsString {
        const TYPE = "IS_XPATH";
        const MESSAGE = "INVALID_XPATH";
        public function execute($value) : bool {
            return (bool) parent::execute($value) && \sizeof(\explode(\DIRECTORY_SEPARATOR, \strip_tags($value))) > 1;
        }
    }
}

