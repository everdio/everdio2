<?php

namespace Modules\Memcached {

    class BaseX extends \Component\Core\Adapter {

        use \Modules\BaseX {
            getResponse as getBaseXResponse;
        }

        final public function getResponse(string $query): string {
            $memcached = new \Modules\Memcached;
            $memcached->id = "basex";
            if (!\sizeof($memcached->getServerList())) {
                $memcached->addServer($this->memcached_server, $this->memcached_port);
            }

            $memcached->key = $this->memcached_prefix . \crc32($query);
            $memcached->find();

            if ($memcached->code === 0) {
                return \unserialize($memcached->data);
            }

            $memcached->data = \serialize($this->getBaseXResponse($query));
            $memcached->ttl = $this->memcached_ttl;
            $memcached->save();

            return (string) \unserialize($memcached->data);
        }
    }

}