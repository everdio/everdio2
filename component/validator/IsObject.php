<?php

namespace Component\Validator {

    class IsObject extends \Component\Validator {

        const TYPE = "object";

        public function execute($value): bool {
            return (bool) \is_object($value);
        }
    }

}