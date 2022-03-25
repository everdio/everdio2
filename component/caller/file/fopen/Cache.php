<?php
namespace Component\Caller\File {
    class Fopen extends \Component\Caller\File {
        public function __construct(string $path) {
            parent::__construct(sprintf("%s.cache", $path), "c+");
        }
        
        public function write($content) : int {
            return (int) parent::write(serialize($content));    
        }
        
        public function read() {
            return \unserialize(parent::read());   
        }
    }
}