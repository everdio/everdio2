<?php

namespace Component\Validator\IsString {

    class IsUrl extends \Component\Validator\IsString {

        const MESSAGE = "INVALID_URL";

        public function execute($value): bool {
            return (bool) (parent::execute($value) && (\parse_url($value, \PHP_URL_HOST)));
        }
    }

}

