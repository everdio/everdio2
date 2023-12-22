<?php

namespace Modules\Memcached {

    use \Component\Validation,
        \Component\Validator;

    final class Model extends \Modules\Memcached {

        public function __construct(array $values = []) {
            parent::__construct([
                "server" => new Validation(false, [new Validator\IsString]),
                "port" => new Validation(false, [new Validator\IsNumeric]),
                "prefix" => new Validation(false, [new Validator\IsString, new Validator\IsEmpty])]);
            $this->store($values);
        }
    }

}
