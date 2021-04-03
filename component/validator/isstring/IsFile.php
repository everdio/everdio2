<?php
namespace Component\Validator\IsString {
    class IsFile extends \Component\Validator\IsString {
        const MESSAGE = "INVALID_FILE";
        public function execute($value) : bool {
            return (bool) parent::execute($value) && (is_file($value) || is_link($value));
        }
    }
}