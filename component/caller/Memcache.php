<?php

namespace Component\Caller {

    class Memcache extends \Component\Caller {

        public function __construct(string $host = "localhost", int $port = 11211) {
            parent::__construct("memcache_%s");
            $this->handle = $this->pconnect($host, $port);
        }

        public function __destruct() {
            $this->close();
        }
    }

}