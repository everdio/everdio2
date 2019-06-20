<?php
namespace Components\Validator\IsString {
    class IsPath extends \Components\Validator\IsString {
        const MESSAGE = "INVALID_PATH";
        
        public function display($value) : bool {
            return (bool) (parent::execute($value) && dirname($value) !== ".");
        }
    }
}

