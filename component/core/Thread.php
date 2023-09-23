<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator;

    class Thread extends \Component\Core {

        use Model;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "model" => new Validation(__DIR__ . \DIRECTORY_SEPARATOR . "Thread.tpl", [new Validator\IsString\IsFile]),
                "namespace" => new Validation(false, [new Validator\IsString]),
                "class" => new Validation(false, [new Validator\IsString, new Validator\IsNumeric]),
                "extends" => new Validation(false, [new Validator\IsString]),
                "parameters" => new Validation(false, [new Validator\IsString])
                    ] + $_parameters);
        }
    }

}

