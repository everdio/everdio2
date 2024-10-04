<?php

namespace Component\Validator\IsString {

    class Uppercase extends \Component\Validator\IsString {

        public function execute($value): bool {
            return (bool) (parent::execute($value) && \ctype_upper($value));
        }
    }

}