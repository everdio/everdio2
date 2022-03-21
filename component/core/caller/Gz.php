<?php
namespace Component\Core\Caller {
    class Gz extends \Component\Core\Caller {
        public function __construct(string $file, string $compression = "w9") {
            parent::__construct("gz");
            $this->resource = $this->open($file, $compression);
        }

        public function __destruct() {
            $this->close();
        }
    }
}