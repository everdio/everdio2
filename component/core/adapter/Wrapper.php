<?php

namespace Component\Core\Adapter {

    use \Component\Validation,
        \Component\Validator;

    abstract class Wrapper extends \Component\Core\Adapter {

        use Threading;
        use Unix;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "pool" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "pids" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "adapter" => new Validation(false, [new Validator\IsArray])
                    ] + $_parameters);
        }                

    }

}