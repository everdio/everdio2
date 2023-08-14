<?php
namespace Component\Core {
    trait Linux {
        protected function load() : float {
            return (float) \substr(\file_get_contents("/proc/loadavg"), 0, 5);  
        }
        
        final public function renice(int $factor = 4) : void {
            if (isset($this->sockets)) {
                $priority = (int) \round((($this->load() / $factor) * 100) * (39 / 100) - 19);
                foreach (\glob($this->sockets . \DIRECTORY_SEPARATOR . "*") as $file) {
                    if (\file_exists("/proc/" . \basename($file))) {
                        \exec(\sprintf("renice %s %s", $priority, \basename($file)));
                    } else {
                        \unlink($file);
                    }
                }
            }
        }               
    }
}

