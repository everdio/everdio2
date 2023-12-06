<?php

namespace Modules\BaseX\Model {

    use \Component\Validation,
        \Component\Validator;

    class Memcached extends \Modules\BaseX\Model {

        public function __construct() {
            parent::__construct([
                "memcached_server" => new Validation("127.0.0.1", [new Validator\IsString]),
                "memcached_port" => new Validation(11211, [new Validator\IsNumeric]),
                "memcached_ttl" => new Validation(3600, [new Validator\IsNumeric]),
                "memcached_prefix" => new Validation("BaseXResponse_", [new Validator\IsString, new Validator\IsEmpty])
            ]);

            $this->model = __DIR__ . \DIRECTORY_SEPARATOR . "Memcached.tpl";
        }
    }

}
