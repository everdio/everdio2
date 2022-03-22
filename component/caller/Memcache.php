<?php
namespace Component\Caller {
    class Memcache extends \Component\Caller {
        public function __construct(string $url, int $port = 11211) {
            parent::__construct("memcache_");
            $this->resource = $this->pconnect($url, $port);
        }
        
        public function __destruct() {
            $this->close();
        }
    }
}