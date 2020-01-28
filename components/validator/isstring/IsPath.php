<?php
namespace Components\Validator\IsString {
    class IsPath extends \Components\Validator\IsString {
        const MESSAGE = "INVALID_PATH";
        
        public function execute($value) : bool {
            return (bool) parent::execute($value) && (sizeof(explode(DIRECTORY_SEPARATOR, $value)) > 1);
        }
    }
}

