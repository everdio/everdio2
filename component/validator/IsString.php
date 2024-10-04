<?php

namespace Component\Validator {

    class IsString extends \Component\Validator {

        const TYPE = "string";

        public function execute($value): bool {
            return (bool) \is_string($value) && $value !== "";
        }
    }

}
