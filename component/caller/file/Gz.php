<?php
namespace Component\Caller\File {    
    class Gz extends \Component\Caller\File {
        public function __construct(string $_file, string $compression = "w9") {
            parent::__construct("gz%s", $_file);
            
            $this->handle = $this->open($_file, $compression);
        }       
    }
}