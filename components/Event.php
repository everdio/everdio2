<?php
namespace Components {
    class Event extends \Exception {
        use Helpers;    
        
        public function getEvent() {

        }
        
        public function __destruct() {
            $file = new \Components\File("event.log", "a");
            $file->store(sprintf("[%s] [%s] [%s in %s(%s)]\n", date("Y-m-d H:i:s"), get_class($this), $this->getMessage(), $this->getFile(), $this->getLine()));       
        }           
    }
}