<?php

namespace Component\Validator {

    class NotArray extends \Component\Validator {

        const TYPE = "not_array";

        public function execute($value): bool {
            return (bool) !\is_array($value);
        }
    }

}
