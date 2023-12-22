<?php

namespace Modules\BaseX\Model {

    use \Component\Validation,
        \Component\Validator;

    final class Memcached extends \Modules\BaseX\Model {

        public function __construct() {
            parent::__construct([
                "memcached" => new Validation(new \Modules\Memcached\Model, [new Validator\IsObject]),
            ]);

            $this->model = __DIR__ . \DIRECTORY_SEPARATOR . "Memcached.tpl";
        } 
    }

}
