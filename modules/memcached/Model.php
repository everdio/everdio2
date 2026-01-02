<?php

namespace Modules\Memcached {

    use \Component\Validation,
        \Component\Validator;

    final class Model extends \Modules\Memcached {

        public function __construct(array $values = []) {
            parent::__construct([
                "id" => new Validation(false, [new Validator\IsString, new Validator\IsInteger]),
                "server" => new Validation("127.0.0.1", [new Validator\IsString]),
                "port" => new Validation(11211, [new Validator\IsInteger]),
                "prefix" => new Validation(false, [new Validator\IsString, new Validator\IsEmpty]),
                "key" => new Validation(false, [new Validator\IsString, new Validator\IsInteger]),
                "ttl" => new Validation(3600, [new Validator\IsInteger]),
                "data" => new Validation(false, [new Validator\IsString, new Validator\IsInteger]),
                "code" => new Validation(false, [new Validator\IsInteger])]);
            $this->store($values);
        }

        public function __dry(): string {
            return (string) \sprintf("new \Modules\Memcached(%s)", parent::__dry());
        }
    }

}