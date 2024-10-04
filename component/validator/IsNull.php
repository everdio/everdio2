<?php

namespace Component\Validator {

    class IsNull extends \Component\Validator {

        const TYPE = "null";

        public function execute($value): bool {
            return (bool) \is_null($value);
        }
    }

}
