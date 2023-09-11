<?php
namespace Component\Caller\File {
    class Fopen extends \Component\Caller\File {
        public function __construct(string $file, string $mode = "r") {
            parent::__construct("f%s", $file);
            $this->handle = $this->open($file, $mode);
        }
        
        public function exists(int $ttl = NULL) : bool {                
            return (bool) parent::exists() && ((!$ttl && \filesize($this->file)) || ($ttl && \filesize($this->file) && (\filemtime($this->file) + $ttl) > \time()));
        }        
    }
}