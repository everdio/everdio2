<?php
namespace Components\File\Cache {
    class Memory extends \Components\File\Cache {
        private $_key = false;
        
        public function __construct(string $path) {
            parent::__construct($path);
            $this->_key = ftok($this->getRealPath(), "t");
        }
        
        private function _open($size, $permission = 0755) {
            if ($size && $this->exists()) {
                return shmop_open($this->_key, ($this->getSize() ? "w" : "c"), $permission, $size);
            }
        }

        public function store($content, $permission = 0755) : int {       
            if (($resource = $this->_open(parent::store($content, $permission)))) {
                return (int) shmop_write($resource, serialize($content), 0);
            }
            
            throw new \RuntimeException(sprintf("can't store memory for %s", $this->getRealPath()));
        }

        public function restore($content = false) {        
            if (($resource = $this->_open($this->getSize()))) {
                return unserialize(shmop_read($resource, 0, $this->getSize()));
            }        
            
            return parent::restore($content);
        }    

        public function delete() : bool {
            if (($resource = $this->_open($this->getSize()))) {
                shmop_delete($resource);
            }
            
            return (bool) parent::delete();
        }
        
        public function __destruct() {
            if ($this->exists() && $this->getSize()) {
                shmop_close($this->_open($this->getSize()));
            }            
        }
    }
}