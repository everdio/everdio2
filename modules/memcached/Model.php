<?php

namespace Modules\Memcached {

    use \Component\Validation,
        \Component\Validator;

    class Model extends \Modules\Memcached {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "server" => new Validation(false, [new Validator\IsString]),
                "port" => new Validation(false, [new Validator\IsNumeric]),
                "prefix" => new Validation(false, [new Validator\IsString, new Validator\IsEmpty])
                    ] + $_parameters);
        }
    }

}
