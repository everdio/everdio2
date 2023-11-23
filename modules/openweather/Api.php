<?php

/*
 * overriding Modules\Node query() and evaluate()
 */

namespace Modules\OpenWeather {

    trait Api {

        use \Modules\Node,
            \Modules\OpenWeather;

        public function query(string $query): \DOMNodeList {
            return (object) $this->xpath((new $this->api)->store($this->restore(["lat", "lon", "lang"]))->getAdapter($this->unique($this->adapter)))->query($query);
        }

        public function evaluate(string $query): int|float|string {
            return $this->xpath((new $this->api)->store($this->restore(["lat", "lon", "lang"]))->getAdapter($this->unique($this->adapter)))->evaluate(\sprintf("count%s", $query));
        }
    }

}