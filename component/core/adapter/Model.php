<?php

namespace Component\Core\Adapter {

    use \Component\Validation,
        \Component\Validator,
        \Component\Core\Parameters;

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
                "storage" => new Validation(\sys_get_temp_dir(), [new Validator\IsString, new Validator\IsString\IsDir]),
                "threads" => new Validation(new Parameters, [new Validator\IsObject]),
                "pool" => new Validation(new Parameters, [new Validator\IsObject]),
                "parameters" => new Validation(false, [new Validator\IsString])
                    ] + $_parameters);
        }

        public function __toString(): string {
            return (string) $this->namespace . "\\" . $this->class;
        }

        abstract public function setup(): void;
    }

}