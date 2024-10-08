<?php

namespace Component\Validator\IsString {

    class IsUrl extends \Component\Validator\IsString {

        public function execute($value): bool {
            return (bool) (parent::execute($value) && \parse_url($value, \PHP_URL_HOST));
        }
    }

}

