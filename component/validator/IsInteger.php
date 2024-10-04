<?php

namespace Component\Validator {

    class IsInteger extends \Component\Validator {

        const TYPE = "integer";

        public function execute($value): bool {
            return (bool) \is_int($value);
        }
    }

}
