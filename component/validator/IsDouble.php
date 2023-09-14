<?php

namespace Component\Validator {

    class IsDouble extends \Component\Validator {

        const TYPE = "IS_DOUBLE";
        const MESSAGE = "INVALID_DOUBLE";

        public function execute($value): bool {
            return (bool) \is_double($value);
        }
    }

}
