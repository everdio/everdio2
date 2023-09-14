<?php

namespace Component\Caller {

    class Ftp extends \Component\Caller {

        public function __construct(string $url) {
            parent::__construct("ftp_%s");
            $this->handle = $this->connect($url);
        }

        final public function execute(): string {
            if (($response = $this->exec()) === false) {
                throw new \RuntimeException($this->error());
            }

            return (string) \trim($response);
        }

        public function __destruct() {
            $this->close();
        }
    }

}