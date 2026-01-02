<?php

namespace Component\Validator\Len {

    class Bigger extends \Component\Validator\Len {

        public function execute($value): bool {
            return (bool) ((\is_string($value) || \is_numeric($value) || \is_integer($value)) && \strlen($value) >= $this->len);
        }
    }

}

