<?php

namespace Component\Validator {

    class IsArray extends \Component\Validator {

        const TYPE = "array";

        public function execute($value): bool {
            return (bool) \is_array($value);
        }
    }

}
