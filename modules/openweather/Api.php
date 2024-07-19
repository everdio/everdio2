<?php

/*
 * overriding Modules\Node query() and evaluate()
 */

namespace Modules\OpenWeather {

    trait Api {

        use \Modules\Node,
            \Modules\OpenWeather;
        
        public function query(string $query): \DOMNodeList {
            return (object) $this->xpath((new $this->api)->store($this->restore(["lat", "lon", "lang"]))->getDOMDocument())->query($query);
        }

        public function evaluate(string $query, string $function): int|float|string {
            return $this->xpath((new $this->api)->store($this->restore(["lat", "lon", "lang"]))->getDOMDocument())->evaluate($function . $query);            
        }       
    }

}