<?php

namespace Component\Validator\IsString\IsFile {

    class IsExtension extends \Component\Validator\IsString\IsFile {

        public function __construct(private string $_extension = false) {
            
        }

        public function execute($value): bool {
            return (bool) (parent::execute($value) && \pathinfo($value, \PATHINFO_EXTENSION) === $this->_extension);
        }
    }

}