<?php
namespace Component\Validator\IsString {
    class HashEquals extends \Component\Validator\IsString {
        const MESSAGE = "HASH_NOT_EQUALS";
        
        public function __construct(private string | int $_hash = false) {
        }
        
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && \hash_equals($this->_hash, $value));
        }
    }
}