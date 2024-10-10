<?php

namespace Modules\IpApi\Adapter {

    use \Component\Validation,
        \Component\Validator;

    class Memcached extends \Modules\IpApi\Adapter {

        public function __construct() {
            parent::__construct([
                "memcached" => new Validation(new \Modules\Memcached\Model, [new Validator\IsObject]),
            ]);

            $this->model = __DIR__ . \DIRECTORY_SEPARATOR . "Memcached.tpl";
        } 
    }

}
