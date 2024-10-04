<?php

namespace Component\Validator\IsString {

    class IsFile extends \Component\Validator\IsString {

        public function execute($value): bool {
            return (bool) parent::execute($value) && (\is_file($value) || \is_link($value));
        }
    }

}