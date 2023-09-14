<?php

namespace Component\Validator {

    class NotArray extends \Component\Validator {

        const TYPE = "NOT_ARRAY";
        const MESSAGE = "IS_ARRAY";

        public function execute($value): bool {
            return (bool) !\is_array($value);
        }
    }

}
