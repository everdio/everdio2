<?php

namespace Component\Validator {

    class IsDefault extends \Component\Validator {

        const TYPE = "default";

        public function execute($value): bool {
            return (bool) (!empty($value) || $value === 0);
        }
    }

}
