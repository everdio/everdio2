<?php

namespace Component\Validator\IsString {

    class IsPath extends \Component\Validator\IsString {

        public function execute($value): bool {
            return (bool) (parent::execute($value) && \str_contains($value, \DIRECTORY_SEPARATOR));
        }
    }

}

