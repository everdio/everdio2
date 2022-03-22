<?php
namespace Component {
    class File extends \SplFileObject {   
        use Dryer;
        public function exists($ttl = false) : bool {                
            return (bool) (!$ttl && ($this->isFile() && $this->getSize())) || ($ttl && ($this->isFile() && $this->getSize()) && ($this->getMTime() + $ttl) > time());
        }
        
        public function store($content, $permission = 0776) : int {
            if ($this->isWritable()) {
                return (int) $this->fwrite($content);
            }
            throw new \RuntimeException("unable to write");
        }

        public function restore($content = false) {                        
            if ($this->isReadable()) {
                $this->rewind();
                while (!$this->eof()) {
                    $content .= $this->fgets();
                }
                
                return $content;
            }
            
            throw new \RuntimeException("unable to read");
        }

        public function delete() : bool {
            if ($this->isWritable()) {
                return (bool) unlink($this->getRealPath());
            }
            
            throw new \RuntimeException("unable to delete");
        }    
        
        public function __dry() : string {
            return (string) \sprintf("new %s(%s, \"w+\")", (string) $this, $this->getRealPath());
        }
    }
}