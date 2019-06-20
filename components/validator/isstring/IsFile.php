<?php
namespace Components\Validator\IsString {
    class IsFile extends \Components\Validator\IsString {
        const MESSAGE = "INVALID_FILE";
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && is_file($value) || is_link($value));
        }
    }
}