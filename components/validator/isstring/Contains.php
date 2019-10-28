<?php
namespace Components\Validator\IsString {
    class Contains extends \Components\Validator\IsString {
        const MESSAGE = "STRING_DOES_NOT_CONTAIN";
        
        private $array = false;        
        
        public function __construct(array $array) {
            $this->array = $array;
        }
        
        public function execute($value) : bool {
            if (parent::execute($value)) {
                foreach ($this->array as $string) {
                    if (strpos($value, (string) $string)) {
                        return (bool) true;
                    }
                }
            }
            
            return (bool) false;
        }
    }
}