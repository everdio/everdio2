<?php
namespace {{namespace}} {
    class {{class}} extends \Component\Core\Adapter {
        use \Modules\Basex {
            getResponse as getBaseXResponse;
        }
        
        public function __construct(array $_parameters = []) {
            parent::__construct({{parameters}} + $_parameters);
        }
        
        final public function getResponse(string $query): string {
            $memcached = new \Modules\Memcached;
            $memcached->id = "basex";
            if (!\sizeof($memcached->getServerList())) {
                $memcached->addServer("127.0.0.1", 11211);
            }

            $memcached->key = "BaseXResponse_" . \crc32($query);
            $memcached->find();

            if ($memcached->code === 0) {
                return \unserialize($memcached->data);
            }

            $time = \microtime(true);

            $memcached->data = \serialize($this->getBaseXResponse($query));

            \file_put_contents("basex.log", \sprintf("%s\t%s\t%s\n", \date("Y-m-d H:i:s"), \round(\microtime(true) - $time, 5), $query), \FILE_APPEND);

            $memcached->ttl = 3600;
            $memcached->save();

            return (string) \unserialize($memcached->data);
        }        
    }
}