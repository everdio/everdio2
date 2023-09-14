<?php

/*
 * overriding Modules\Node query() and evaluate()
 */

namespace Modules\IpApi {

    trait Api {

        use \Modules\Node,
            \Modules\IpApi;

        public function query(string $query): \DOMNodeList {
            $xpath = new \DOMXPath((new $this->api)->store($this->restore(["ip"]))->getAdapter($this->unique($this->adapter)));
            return (object) $xpath->query($query);
        }

        public function evaluate(string $query): int {
            $xpath = new \DOMXPath((new $this->api)->store($this->restore(["ip"]))->getAdapter($this->unique($this->adapter)));
            return (int) $xpath->evaluate(\sprintf("count%s", $query));
        }
    }

}