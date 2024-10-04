<?php

namespace Component\Validator {

    class IsDouble extends \Component\Validator {

        const TYPE = "double";

        public function execute($value): bool {
            return (bool) \is_double($value);
        }
    }

}
