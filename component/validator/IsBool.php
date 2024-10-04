<?php

namespace Component\Validator {

    class IsBool extends \Component\Validator {

        const TYPE = "bool";

        public function execute($value): bool {
            return (bool) \is_bool($value);
        }
    }

}
