<?php

namespace Modules {

    use \Component\Validation,
        \Component\Validator;

    class Memcached extends \Component\Core\Adapter\Wrapper {

        final public function __construct() {
            parent::__construct([
                "key" => new Validation(false, [new Validator\IsString, new Validator\IsNumeric]),
                "ttl" => new Validation(false, [new Validator\IsNumeric]),
                "data" => new Validation(false, [new Validator\IsString, new Validator\IsNumeric]),
                "code" => new Validation(false, [new Validator\IsInteger])
            ]);
        }

        final public function find(): self {
            return $this->store(["data" => $this->get($this->key), "code" => (int) $this->getResultCode()]);
        }

        final public function save(): self {
            $this->add($this->key, $this->data, $this->ttl);
            return (object) $this;
        }

        final protected function __init(): object {
            return (object) new \Memcached($this->id);
        }
    }

}