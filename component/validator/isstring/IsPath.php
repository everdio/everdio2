<?php
namespace Component\Validator\IsString {
    class IsPath extends \Component\Validator\IsString {
        const TYPE = "IS_PATH";
        const MESSAGE = "INVALID_PATH";
        public function execute($value) : bool {       
            return (bool) (parent::execute($value) && $value === \preg_replace("/[^A-Za-z0-9\/]/", false, $value));
            //return (bool) (parent::execute($value) && (\strlen($value) === \strlen(\strip_tags($value)) && \sizeof(\explode(\DIRECTORY_SEPARATOR, \strip_tags($value)))));
        }
    }
}

