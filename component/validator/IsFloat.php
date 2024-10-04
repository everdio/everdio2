<?php

namespace Component\Validator {

    class IsFloat extends \Component\Validator {

        const TYPE = "float";

        public function execute($value): bool {
            return (bool) \is_float($value);
        }
    }

}
