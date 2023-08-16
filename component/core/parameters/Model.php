<?php

namespace Component\Core\Parameters {

    use \Component\Validation,
        \Component\Validator;

    class Model extends \Component\Core\Parameters {

        use \Component\Core\Model;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "model" => new Validation(__DIR__ . \DIRECTORY_SEPARATOR . "Model.tpl", [new Validator\IsString\IsFile]),
                "namespace" => new Validation(false, [new Validator\IsString]),
                "class" => new Validation(false, [new Validator\IsString]),
                "use" => new Validation(false, [new Validator\IsString]),
                "mapper" => new Validation(false, [new Validator\IsString])
                    ] + $_parameters);
        }
    }

}