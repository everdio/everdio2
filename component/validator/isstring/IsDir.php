<?php

namespace Component\Validator\IsString {

    class IsDir extends \Component\Validator\IsString {

        public function execute($value): bool {
            return (boolean) (parent::execute($value) && \is_dir($value));
        }
    }

}