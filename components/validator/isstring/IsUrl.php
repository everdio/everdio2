<?php
namespace Components\Validator\IsString {
    class IsUrl extends \Components\Validator\IsString {
        const MESSAGE = "NO_URL";
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && (parse_url($value, PHP_URL_SCHEME) && parse_url($value, PHP_URL_HOST)));
        }
    }
}

