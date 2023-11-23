<?php

/*
 * overriding Modules\Node query() and evaluate()
 */

namespace Modules\IpApi {

    trait Api {

        use \Modules\Node,
            \Modules\IpApi;

        public function query(string $query): \DOMNodeList {
            return (object) $this->xpath((new $this->api)->store($this->restore(["ip"]))->getAdapter($this->unique($this->adapter)))->query($query);
        }

        public function evaluate(string $query): int|float|string {
            return $this->xpath((new $this->api)->store($this->restore(["ip"]))->getAdapter($this->unique($this->adapter)))->evaluate(\sprintf("count%s", $query));
        }
    }

}