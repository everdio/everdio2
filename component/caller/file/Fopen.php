<?php
namespace Component\Caller\File {
    class Fopen extends \Component\Caller\File {
        public function __construct(string $file, string $mode = "r") {
            parent::__construct("f%s", $file);
            $this->handle = $this->open($file, $mode);
        }
        
        public function exists(int $ttl = NULL) : bool {                
            return (bool) parent::exists() && ((!$ttl && $this->getSize()) || ($ttl && $this->getSize() && (\filemtime($this->file) + $ttl) > \time()));
        }        
        
        public function getSize() : int {
            $this->seek(0, \SEEK_END);
            $size = $this->tell();
            $this->seek(0);
            return (int) $size;
        }
    }
}