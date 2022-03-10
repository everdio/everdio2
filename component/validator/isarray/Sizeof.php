<?php
namespace Component\Validator\IsArray {
    abstract class Sizeof extends \Component\Validator\IsArray {
        const MESSAGE = "INVALID_ARRAY_SIZE";
        public function __construct(protected int $sizeof = 0) {
            
        }
        
        public function getSize() : int {
            return (int) $this->sizeof;
        }
        
        public function __dry() : string {
            return (string) \sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->sizeof));
        }          
    }
}

