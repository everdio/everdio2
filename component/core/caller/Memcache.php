<?php
namespace Component\Core\Caller {
    class Memcache extends \Component\Core\Caller {
        public function __construct(string $url, int $port = 11211) {
            parent::__construct("memcache");
            $this->resource = $this->pconnect($url, $port);
        }
        
        public function __destruct() {
            $this->close();
        }
    }
}