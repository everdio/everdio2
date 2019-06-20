<?php
namespace Components {
    class Event extends \Exception {
        use Helpers;        
        use Dryer;           
        
        public function __dry() : string {
            $traces = $this->getTrace();
            return (string) sprintf("[%s] [%s] [%s(%s)]\n", date("Y-m-d H:i:s"), $this->getMessage(), $traces[0]["file"], $traces[0]["line"]);
        }
        
        public function __destruct() {
            $file = new \Components\File("event.log", "a");
            $file->store($this->__dry());       
        }           
    }
}