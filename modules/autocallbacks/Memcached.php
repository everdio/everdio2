<?php   
namespace Modules\Autocallbacks {
    class Memcached extends \Modules\Autocallbacks {
        protected function _fromCache(object $object, string $calledbacks, string $key, int $ttl = 3600) {
            if ((!$cache = $this->memcached->get($key)) && $this->memcached->getResultCode() !== 0) {
                $cache = \serialize($object->callback($calledbacks));
                $this->memcached->add($key, $cache, $ttl);
            }            
            
            return \unserialize($cache);
        }
    }
}
