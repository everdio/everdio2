<?php
namespace Modules\OpenWeather {
    trait Api {
        use \Modules\Node, \Modules\OpenWeather;
        public function query(string $query) : \DOMNodeList {
            if (isset($this->api)) {
                $xpath = new \DOMXPath($this->api::construct()->store($this->restore(["lat", "lon", "lang"]))->fetch());
                return (object) $xpath->query($query);
            }
            
            throw new \LogicException("unknown or invalid API");
        }
    }
}