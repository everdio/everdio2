<?php

namespace Modules {

    class Memcached extends \Component\Core\Adapter\Wrapper {

        public function __construct(array $_parameters = []) {
            parent::__construct($_parameters);
            $this->adapter = ["id"];
        }

        final protected function addAdapter(): object {
            return (object) new \Memcached($this->id);
        }
        
        final public function find(): self {
            return $this->store(["data" => $this->get($this->key), "code" => (int) $this->getResultCode()]);
        }

        final public function save(): self {
            $this->add($this->key, $this->data, $this->ttl);
            return (object) $this;
        }
    }

}