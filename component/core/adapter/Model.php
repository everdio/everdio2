<?php

namespace Component\Core\Adapter {

    use \Component\Validation,
        \Component\Validator;

    abstract class Model extends \Component\Core\Adapter {

        use \Component\Core\Model;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "adapter" => new Validation(false, [new Validator\IsArray]),
                "overwrite" => new Validation(true, [new Validator\IsBool]),
                "model" => new Validation(__DIR__ . \DIRECTORY_SEPARATOR . "Model.tpl", [new Validator\IsString\IsFile]),
                "namespace" => new Validation(false, [new Validator\IsString]),
                "class" => new Validation(false, [new Validator\IsString]),
                "use" => new Validation(false, [new Validator\IsString]),
                "parameters" => new Validation(false, [new Validator\IsString])
                    ] + $_parameters);
        }

        public function __toString(): string {
            return (string) $this->namespace . "\\" . $this->class;
        }

        abstract public function setup(): void;
    }

}