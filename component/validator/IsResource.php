<?php

namespace Component\Validator {

    class IsResource extends \Component\Validator {

        const TYPE = "resource";

        public function execute($value): bool {
            return (bool) \is_resource($value);
        }
    }

}
