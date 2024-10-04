<?php

namespace Component\Validator {

    class IsEmpty extends \Component\Validator {

        const TYPE = "empty";

        public function execute($value): bool {
            return (bool) $value === NULL || empty($value);
        }
    }

}