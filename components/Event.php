<?php
namespace Components {
    class Event extends \Exception {
        use Helpers;        
        use Dryer;           
        
        public function __dry() : string {
            return (string) sprintf("[%s] [%s] [%s]\n", date("Y-m-d H:i:s"), get_class($this), $this->getMessage());
        }
        
        public function __destruct() {
            $file = new \Components\File("event.log", "a");
            $file->store($this->__dry());       
        }           
    }
}