<?php

namespace Modules\IpApi\Model {

    use \Component\Validation,
        \Component\Validator;

    class Memcached extends \Modules\IpApi\Model {

        public function __construct() {
            parent::__construct([
                "memcached" => new Validation(new \Modules\Memcached\Model, [new Validator\IsObject]),
            ]);

            $this->model = __DIR__ . \DIRECTORY_SEPARATOR . "Memcached.tpl";
        } 
    }

}
