<?php
namespace Component\Caller {
    class Dir extends \Component\Caller {
        public function __construct(string $dir) {
            parent::__construct("%sdir");
            $this->handle = $this->open($dir);
        }        
 
        public function __destruct() {
            $this->close();
        }
    }
}