<?php

namespace Component\Validator\IsString {

    class IsXPath extends \Component\Validator\IsString {

        const TYPE = "xpath";

        public function execute($value): bool {
            return (bool) (parent::execute($value) && \str_contains(\strip_tags($value), \DIRECTORY_SEPARATOR));
        }
    }

}

