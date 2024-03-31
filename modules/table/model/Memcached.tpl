<?php
namespace {{namespace}} {

    class {{class}} extends \Modules\Memcached\Adapter {
        use {{use}} {
            query as apiQuery;
        }
        
        public function __construct(array $_parameters = []) {
            parent::__construct({{parameters}} + $_parameters);
        }
        
        final public function query(string $query, bool $memcached = true): string {
            if ($memcached) {
                $this->memcached->key = $this->memcached->prefix . \crc32($query);

                if ($this->memcached->find()->code === 0) {
                    return \unserialize($this->memcached->data);
                }

                $this->memcached->data = \serialize($this->apiQuery($query));
                $this->memcached->save();

                return (string) \unserialize($this->memcached->data);
            }
            
            return (string) $this->apiQuery($query);
        }        
    }
    
}