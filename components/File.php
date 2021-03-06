<?php
namespace Components {
    class File extends \SplFileObject {   
        use Dryer;
        public function exists($ttl = false) : bool {                
            return (bool) (!$ttl && ($this->isFile() && $this->getSize())) || ($ttl && ($this->isFile() && $this->getSize()) && ($this->getMTime() + $ttl) > time());
        }
        
        public function store($content) : int {
            if ($this->isWritable()) {
                return (int) $this->fwrite($content);
            }
            throw new \Exception($this->getRealPath());
        }

        public function restore($content = false) {                        
            if ($this->isReadable()) {
                $this->rewind();
                while (!$this->eof()) {
                    $content .= $this->fgets();
                }
                return $content;
            }
            throw new \Exception($this->getRealPath());
        }

        public function delete() : bool {
            if ($this->isReadable() && $this->isWritable()) {
                return (bool) unlink($this->getRealPath());
            }
            throw new \Exception($this->getRealPath());
        }    
        
        public function __dry() : string {
            return (string) sprintf("new %s(%s, \"w+\")", (string) $this, $this->getRealPath());
        }
    }
}