<?php
namespace Components\Core\Caller {
    class Memcache extends \Components\Core\Caller {
        public function __construct(string $url, int $port = 11211) {
            parent::__construct("memcache");
            $this->resource = $this->pconnect($url, $port);
        }
        
        public function __destruct() {
            $this->close();
        }
    }
}