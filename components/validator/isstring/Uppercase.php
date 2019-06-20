<?php
namespace Components\Validator\IsString {
    class Uppercase extends \Components\Validator\IsString {
        const MESSAGE = "NOT_UPPERCASE";
        
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && ctype_upper($value));
        }
    }
}