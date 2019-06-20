<?php
namespace Components\Validator {
    abstract class Len extends \Components\Validator {
        const TYPE = "LEN";
        const MESSAGE = "INVALID_LENGTH";
        
        protected $len = 0;
        
        public function __construct(int $len) {
            $this->len = $len;
        }
        
        public function getLen() : int {
            return (int) $this->len;
        }
        
        public function __dry() : string {
            return (string) sprintf("new \%s(%s)", (string) $this, $this->len);
        }        
    }
}

