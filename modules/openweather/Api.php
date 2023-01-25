<?php
namespace Modules\OpenWeather {
    trait Api {
        use \Modules\Node, \Modules\OpenWeather;
        public function query(string $query) : \DOMNodeList {
            if (isset($this->api)) {
                $xpath = new \DOMXPath($this->api::construct()->store($this->restore(["lat", "lon", "lang"]))->getAdapter($this->unique($this->adapter)));
                return (object) $xpath->query($query);
            }
            
            throw new \LogicException("API not set");
        }
        
        public function evaluate(string $query) : int {
            if (isset($this->api)) {
                $xpath = new \DOMXPath($this->api::construct()->store($this->restore(["lat", "lon", "lang"]))->getAdapter($this->unique($this->adapter)));
                return (int) $xpath->evaluate(\sprintf("count%s", $query));
            }
            
            throw new \LogicException("API not set");
        }        
    }
}