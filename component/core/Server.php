<?php
namespace Component\Core {
    trait Server {
        final public function throttle(array $processmanagers, int $factor = 2, int $usleep = 10000) : void {
            switch ($this->oid) {
                case "linux":
                    $this->throttle = \round($usleep * $this->_renice($processmanagers, $factor));
                    break;
            }
        }
        
        /*
         * fetching processes based on pm (process manager) and resetting nicesess
         * all running proceses matching the pm will be resetted based on current load
         * throtteling applies to dispatching only
         * To allow renice, add the following in to limits.conf:         
         |   <domain> <type> <item> <value>
         |   *        -      nice   -20
        */
        private function _renice(array $processmanagers, int $factor = 2) : float {
            $fopen = new \Component\Caller\File\Fopen("/proc/loadavg", "r");                                
            $load = (float) $this->hydrate($fopen->read(5));                                                
            if (\sizeof($processmanagers)) {
                $priority = (int) \round((($load / ($this->hydrate(\exec("nproc")) / $factor)) * 100) * (39 / 100) - 19);
                foreach (\glob("/proc/*/status") as $entry) {
                    if (\is_integer(($pid = $this->hydrate(\basename(\dirname($entry)))))) {
                        try {
                            $fopen = new \Component\Caller\File\Fopen($entry, "r");
                            if (\in_array(\str_replace("Name:\t", "", \trim($fopen->gets())), $processmanagers)) {
                                \exec(\sprintf("renice %s %s", $priority, $pid));
                            }
                        } catch (\Exception $ex) {
                            //ignore since the process is already gone
                        }
                    }
                }                
            }

            return (float) $load;                
        }         
    }
}