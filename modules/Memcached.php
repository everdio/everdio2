<?php

namespace Modules {

    use \Component\Validation,
        \Component\Validator;

    class Memcached extends \Component\Core\Adapter\Wrapper {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "key" => new Validation(false, [new Validator\IsString, new Validator\IsInteger]),
                "ttl" => new Validation(false, [new Validator\IsInteger]),
                "data" => new Validation(false, [new Validator\IsString, new Validator\IsInteger]),
                "code" => new Validation(false, [new Validator\IsInteger])
                    ] + $_parameters);
        }

        final protected function __init(): object {
            return (object) new \Memcached($this->id);
        }

        final public function find(): self {
            return $this->store(["data" => $this->get($this->key), "code" => (int) $this->getResultCode()]);
        }

        final public function save(): self {
            $this->add($this->key, $this->data, $this->ttl);
            return (object) $this;
        }

        final public function __destruct() {
            $this->quit();
        }
    }

}