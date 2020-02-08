<?php
namespace Components\File\Cache {
    class Memory extends \Components\File\Cache {
        private $ftok = false;

        public function __construct(string $path) {
            parent::__construct($path);
            $this->ftok = ftok($this->getRealPath(), "t");
        }
        
        private function _open($size, $permission = 0755) {
            if ($this->exists()) {
                return shmop_open($this->ftok, "c", $permission, $size);
            }
            
            throw new \Components\File\Event(sprintf("can't open memory %s", $this->getRealPath()));
        }

        public function store($content, $permission = 0777) : int {       
            if (($key = $this->_open(parent::store($content, $permission)))) {
                return (int) shmop_write($key, serialize($content), 0);
            }
        }

        public function restore($content = false) {        
            if (($key = $this->_open($this->getSize()))) {
                return unserialize(shmop_read($key, 0, $this->getSize()));
            }        
            return parent::restore($content);
        }    

        public function delete() : bool {
            if (($key = $this->_open($this->getSize()))) {
                shmop_delete($key);
                shmop_close($key);
            }
            
            return (bool) parent::delete();
        }
    }
}