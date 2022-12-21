<?php
namespace Component\Caller {
    class Dir extends \Component\Caller {
        public function __construct(string $dir) {
            parent::__construct("%sdir");
            $this->handle = $this->open($dir);
        }        
        
        public function recursive(array $entries = []) {
            while (($entry = $this->read()) !== false) {
                if ($entry !== "." && $entry !== "..") {
                    if (\is_dir($entry)) {
                        $this->ch($entry);
                        $entries[$entry] = $this->recursive();
                    } elseif (\is_file($entry)) {
                        $entries[$entry] = \basename($entry);
                    }
                }
            }
            
            return (array) $entries;
        }
        
        public function __destruct() {
            $this->close();
        }
    }
}