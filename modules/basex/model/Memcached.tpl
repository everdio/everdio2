<?php
namespace {{namespace}} {

    class {{class}} extends \Component\Core\Adapter {
        use {{use}} {
            getResponse as getApiResponse;
        }
        
        public function __construct(array $_parameters = []) {
            parent::__construct({{parameters}} + $_parameters);
        }
        
        final public function getResponse(string $query): string {
            $memcached = new \Modules\Memcached;
            $memcached->id = $this->memcached_id;
            if (!\sizeof($memcached->getServerList())) {
                $memcached->addServer($this->memcached_server, $this->memcached_port);
            }         
        
            $memcached->key = $this->memcached_prefix . \crc32($query);
            
            if ($memcached->find()->code === 0) {
                return \unserialize($memcached->data);
            }

            $memcached->data = \serialize($this->getApiResponse($query));
            $memcached->ttl = $this->memcached_ttl;
            $memcached->save();

            return (string) \unserialize($memcached->data);
        }        
    }
    
}