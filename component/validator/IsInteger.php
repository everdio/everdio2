<?php

namespace Component\Validator {

    class IsInteger extends \Component\Validator {

        const TYPE = "IS_INT";
        const MESSAGE = "INVALID_INTEGER";

        public function execute($value): bool {
            return (bool) \is_int($value);
        }
    }

}
