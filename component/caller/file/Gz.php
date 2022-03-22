<?php
namespace Component\Caller\File {    
    class Gz extends \Component\Caller\File {
        public function __construct(string $_file, string $compression = "w9") {
            parent::__construct("gz", $_file);
            $this->resource = $this->open($_file, $compression);
        }       

        public function __destruct() {
            $this->close();
        }
    }
}