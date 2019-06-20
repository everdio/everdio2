<?php
namespace Components\Validator\IsString {
    class HashEquals extends \Components\Validator\IsString {
        const MESSAGE = "HASH_NOT_EQUALS";
        
        private $hash = false;        
        
        public function __construct($hash) {
            $this->hash = $hash;
        }
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && hash_equals($this->hash, $value));
        }
    }
}