<?php
namespace Component\Validator\IsString {
    class IsDir extends \Component\Validator\IsString {
        const MESSAGE = "INVALID_DIR";
        public function execute($value) : bool {
            return (boolean) (parent::execute($value) && \is_dir($value));
        }
    }
}