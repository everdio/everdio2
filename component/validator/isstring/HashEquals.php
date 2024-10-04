<?php

namespace Component\Validator\IsString {

    class HashEquals extends \Component\Validator\IsString {

        public function __construct(private string|int $_hash = false) {}

        public function execute($value): bool {
            return (bool) (parent::execute($value) && \hash_equals($this->_hash, $value));
        }
    }

}