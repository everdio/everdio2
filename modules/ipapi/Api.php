<?php

/*
 * overriding Modules\Node query() and evaluate()
 */

namespace Modules\IpApi {

    trait Api {

        use \Modules\Node,
            \Modules\IpApi;

        public function query(string $query): \DOMNodeList {
            return (object) $this->xpath((new $this->api)->store($this->restore(["ip"]))->getDOMDocument())->query($query);
        }

        public function evaluate(string $query, string $function): int|float|string {
            return (object) $this->xpath((new $this->api)->store($this->restore(["ip"]))->getDOMDocument())->evaluate($function . $query);            
        }
    }

}