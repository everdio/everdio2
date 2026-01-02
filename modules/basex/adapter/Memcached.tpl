<?php
namespace {{namespace}} {

    class {{class}} extends \Modules\Memcached\Adapter {
        use {{use}} {
            getResponse as getApiResponse;
        }
        
        public function __construct(array $_parameters = []) {
            parent::__construct({{parameters}} + $_parameters);
        }
        
        final public function getResponse(string $query, bool $memcached = true): string {
            if ($memcached) {
                $key = $this->memcached->prefix . \crc32($query);

                if (($data = $this->memcached->get($key)) && $this->memcached->getResultCode() === 0) {
                    return \unserialize($data);
                }

                $this->memcached->add($key, \serialize(($data = $this->getApiResponse($query))), $this->memcached->ttl);

                return $data;
            }

            return (string) $this->getApiResponse($query);
        }    
    }
    
}