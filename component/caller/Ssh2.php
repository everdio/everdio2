<?php

namespace Component\Caller {

    class Ssh2 extends \Component\Caller {

        public function __construct(string $host, int $port = 22) {
            parent::__construct("ssh2_%s");
            $this->handle = $this->connect($host, $port);
        }

        public function __destruct() {
            $this->disconnect();
        }
    }

}