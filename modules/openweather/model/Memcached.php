<?php

namespace Modules\OpenWeather\Model {

    use \Component\Validation,
        \Component\Validator;

    class Memcached extends \Modules\OpenWeather\Model {

        public function __construct() {
            parent::__construct([
                "memcached_server" => new Validation(false, [new Validator\IsString]),
                "memcached_port" => new Validation(false, [new Validator\IsNumeric]),
                "memcached_ttl" => new Validation(false, [new Validator\IsNumeric]),
                "memcached_prefix" => new Validation(false, [new Validator\IsString, new Validator\IsEmpty])
            ]);

            $this->model = __DIR__ . \DIRECTORY_SEPARATOR . "Memcached.tpl";
        }
    }

}
