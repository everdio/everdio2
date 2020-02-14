<?php
namespace Components\File\Cache {
    class Memory extends \Components\File\Cache {
        private $_key = false;
        private $_id = false;
        public function __construct(string $path) {
            parent::__construct($path);
            $this->_key = ftok($this->getRealPath(), "t");
        }
        
        private function _open($size, $permission = 0755) {
            if ($this->exists()) {
                if ($this->_id === false) {
                    $this->_id = shmop_open($this->_key, "c", $permission, $size);
                }
                return $this->_id;
            }
            throw new \Components\File\Event(sprintf("can't open memory for %s", $this->getRealPath()));
        }

        public function store($content, $permission = 0755) : int {       
            if (($resource = $this->_open(parent::store($content, $permission)))) {
                return (int) shmop_write($resource, serialize($content), 0);
            }
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
            if (($resource = $this->_open($this->getSize()))) {
                shmop_close($resource);
            }            
        }
    }
}