<?php

namespace Modules\Table\Model {

    use \Component\Validation,
        \Component\Validator;

    final class Memcached extends \Modules\Table\Model {

        public function __construct() {
            parent::__construct([
                "memcached" => new Validation(new \Modules\Memcached\Model, [new Validator\IsObject]),
            ]);

            $this->model = __DIR__ . \DIRECTORY_SEPARATOR . "Memcached.tpl";
        }
    }

}
