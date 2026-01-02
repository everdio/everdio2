<?php
namespace {{namespace}} {

    class {{class}} extends \Modules\Memcached\Adapter {
        use {{use}} {
            getResponse as getApiResponse;
        }
        
        public function __construct(array $_parameters = []) {
            parent::__construct({{parameters}} + $_parameters);
        }
        
        final public function getResponse(string $url): string {
            $this->memcached->key = $this->memcached->prefix . \crc32($url);

            if ($this->memcached->find()->code === 0) {
                return \unserialize($this->memcached->data);
            }

            $this->memcached->data = \serialize($this->getApiResponse($url));
            $this->memcached->save();

            return (string) \unserialize($this->memcached->data);
        }        
    }
}