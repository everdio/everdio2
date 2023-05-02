<?php
namespace {{namespace}} {
    class {{class}} extends \Component\Core\Adapter {
        use {{use}};      
        public function __construct(array $_parameters = []) {
            parent::__construct({{mapper}} + $_parameters);
        }

        static public function construct(array $_parameters = []) : self {
            return (object) new {{class}}($_parameters);
        }                
        
        
        final public function getResponse(string $query, int $ttl = 3600) : string {
            $memcached = new \Memcached($this->database);
            $memcached->setOption(\Memcached::OPT_COMPRESSION, true);
            if (empty($memcached->getServerList())) {
                $memcached->addServer("127.0.0.1", 11211);
            }            

            $key = \md5($query);
            
            if (!$memcached->get($key) && $memcached->getResultCode() !== 0) {
                $memcached->add($key, (string) parent::getResponse($query), $ttl);
            }

            return (string) $memcached->get($key);
        }
    }
}