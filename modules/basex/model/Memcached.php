<?php

namespace Modules\BaseX\Model {

    class Memcached extends \Modules\BaseX\Model {

        public function __construct() {
            parent::__construct();
            $this->model = __DIR__ . \DIRECTORY_SEPARATOR . "Memcached.tpl";
        }
    }

}
