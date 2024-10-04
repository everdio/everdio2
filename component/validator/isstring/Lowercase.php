<?php

namespace Component\Validator\IsString {

    class Lowercase extends \Component\Validator\IsString {

        public function execute($value): bool {
            return (bool) (parent::execute($value) && \ctype_lower($value));
        }
    }

}