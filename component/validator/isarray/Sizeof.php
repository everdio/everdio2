<?php
namespace Component\Validator\IsArray {
    abstract class Sizeof extends \Component\Validator\IsArray {
        const MESSAGE = "INVALID_ARRAY_SIZE";
        protected $sizeof = 0;
        public function __construct(int $sizeof = 0) {
            $this->sizeof = $sizeof;
        }
        
        public function getSize() : int {
            return (int) $this->sizeof;
        }
        
        public function __dry() : string {
            return (string) sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->sizeof));
        }          
    }
}

