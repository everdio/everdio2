<?php
namespace Components\Validator\IsString {
    class IsUrl extends \Components\Validator\IsString {
        const MESSAGE = "NO_URL";
        
        public function display($value) : bool {
            return (bool) (parent::execute($value) && fopen($value, "r"));
        }
    }
}

