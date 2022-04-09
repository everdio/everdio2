<?php
namespace Component\Caller\File {
    class Fopen extends \Component\Caller\File {
        public function __construct(string $_file, string $mode = NULL) {
            parent::__construct("f%s", $_file);
            $this->handle = $this->open($_file, $mode);
        }
        
        public function exists($ttl = false) : bool {                
            return (bool) (!$ttl && $this->getSize()) || ($ttl && $this->getSize() && ($this->getMTime() + $ttl) > time());
        }        
        
        public function getSize() : int {
            $this->seek(0, \SEEK_END);
            $size = $this->tell();
            $this->seek(0);
            return (int) $size;
        }
        
        public function __destruct() {
            $this->close();
        }
    }
}