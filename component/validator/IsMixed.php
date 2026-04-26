<?php

namespace Component\Validator {

    class IsMixed extends \Component\Validator {

        const TYPE = "mixed";

        public function execute($value): bool {
            return (bool) (\is_string($value) || \is_integer($value));
        }
    }

}
