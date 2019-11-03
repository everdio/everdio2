<?php
namespace Components\File\Cache {
    class Memory extends \Components\File\Cache {
        
        private $ftok = false;

        public function __construct($path) {
            parent::__construct($path);
            $this->ftok = ftok($this->getRealPath(), "t");        
        }
        
        private function _open($size, $flag = "c", $permission = 0755) {
            if ($this->exists()) {
                return shmop_open($this->ftok, $flag, $permission, $size);
            }
            
            throw new \Components\Error(sprintf("can't create memory %s", $this->getRealPath()));
        }

        public function store($content) : int {       
            if (($key = $this->_open(parent::store($content)))) {
                return (int) shmop_write($key, serialize($content), 0);
            }
        }

        public function restore() {        
            if (($key = $this->_open($this->getSize()))) {
                return unserialize(shmop_read($key, 0, $this->getSize()));
            }        
        }    

        public function delete() : bool {
            if (($key = $this->_open($this->getSize()))) {
                return (bool) (shmop_delete($key) && shmop_close($key) && parent::delete());
            }
        }
    }
}