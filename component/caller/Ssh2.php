<?php

namespace Component\Caller {

    class Ssh2 extends \Component\Caller {

        public function __construct(string $host, int $port = 22) {
            parent::__construct("ssh2_%s");
            $this->handle = $this->connect($host, $port);
        }
        /*
         * allows easy reconnects
         */
        final public function connect(string $host, int $port = 22) {
            return \ssh2_connect($host, $port);
        }
        
        /*
         * sending a command and wait for output;
         */
        final public function exec(string $command) {
            $stream = parent::exec($command);
            \stream_set_blocking($stream, true);           
            return \stream_get_contents($stream);
        }

        public function __destruct() {
            $this->disconnect();
        }
    }

}