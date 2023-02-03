<?php
namespace Modules\IpApi {
    trait Api {
        use \Modules\Node, \Modules\IpApi;
        public function query(string $query) : \DOMNodeList {
            if (isset($this->api)) {
                $xpath = new \DOMXPath($this->api::construct()->store($this->restore(["ip"]))->getAdapter($this->unique($this->adapter)));
                return (object) $xpath->query($query);
            }
            
            throw new \LogicException("API does not exist");
        }
        
        public function evaluate(string $query) : int {
            if (isset($this->api)) {
                $xpath = new \DOMXPath($this->api::construct()->store($this->restore(["ip"]))->getAdapter($this->unique($this->adapter)));
                return (int) $xpath->evaluate(\sprintf("count%s", $query));
            }
            
            throw new \LogicException("API does not exist");
        }        
    }
}