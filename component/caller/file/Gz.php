<?php
namespace Component\Caller\File {    
    class Gz extends \Component\Caller\File {
        public function __construct(string $file, string $compression = "w9") {
            parent::__construct("gz%s", $file);
            $this->handle = $this->open($file, $compression);
        }       
    }
}