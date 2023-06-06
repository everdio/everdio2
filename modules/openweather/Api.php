<?php
/*
 * overriding Modules\Node query() and evaluate()
 */
namespace Modules\OpenWeather {
    trait Api {
        use \Modules\Node, \Modules\OpenWeather;
        public function query(string $query) : \DOMNodeList {
            $xpath = new \DOMXPath($this->api::construct()->store($this->restore(["lat", "lon", "lang"]))->getAdapter($this->unique($this->adapter)));
            return (object) $xpath->query($query);
        }
        
        public function evaluate(string $query) : int {
            $xpath = new \DOMXPath($this->api::construct()->store($this->restore(["lat", "lon", "lang"]))->getAdapter($this->unique($htis->restore($this->adapter))));
            return (int) $xpath->evaluate(\sprintf("count%s", $query));
        }        
    }
}