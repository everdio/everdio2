<?php
namespace Component\Caller {
    class Dir extends \Component\Caller {
        public function __construct(public string $dir, int $mode = 0776, string $group = "www-data") {
            parent::__construct("%sdir");
            
            if (!\is_dir($this->dir) && \mkdir($this->dir, $mode, true)) {
                \chgrp($this->dir, $group);
            }
            
            $this->handle = $this->open($this->dir);
        }        
 
        public function __destruct() {
            $this->close();
        }
    }
}