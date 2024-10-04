<?php

namespace Component\Validator {

    class NotEmpty extends \Component\Validator {

        const TYPE = "not_empty";

        public function execute($value): bool {
            return (bool) !empty($value);
        }
    }

}
