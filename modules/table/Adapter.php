<?php

namespace Modules\Table {

    use Component\Validation,
        Component\Validator;

    abstract class Adapter extends \Component\Core\Adapter {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "adapter" => new Validation(false, [new Validator\IsArray]),
                "dsn" => new Validation(false, [new Validator\IsString]),
                    ] + $_parameters);
        }

        abstract public function generate(array $parameters = []): void;
    }

}
