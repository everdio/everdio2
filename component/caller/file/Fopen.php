<?php
namespace Component\Caller\File {
    class Fopen extends \Component\Caller\File {
        public function __construct(string $_file, string $mode = "r") {
            parent::__construct("f%s", $_file);
            $this->handle = $this->open($_file, $mode);
        }
        
        public function exists($ttl = false) : bool {                
            return (bool) (!$ttl && $this->getSize()) || ($ttl && $this->getSize() && (\filemtime($this->getPath()) + $ttl) > time());
        }        
        
        public function getSize() : int {
            $this->seek(0, \SEEK_END);
            $size = $this->tell();
            $this->seek(0);
            return (int) $size;
        }
    }
}