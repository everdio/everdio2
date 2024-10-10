<?php

namespace Component\Validator {

    class IsNumeric extends \Component\Validator {

        const TYPE = "numeric";

        public function execute($value): bool {
            return (bool) !\is_integer($value) && (\is_numeric($value) && \is_String($value));
        }
    }

}
