<?php

namespace Modules\Memcached {

    abstract class Adapter extends \Component\Core\Adapter {
        
        public function __construct(array $_parameters = []) {
            parent::__construct($_parameters);

            if (!\sizeof($this->memcached->getServerList())) {
                $this->memcached->addServer($this->memcached->server, $this->memcached->port);
            }
        }
    }

}