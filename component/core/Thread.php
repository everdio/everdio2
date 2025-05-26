<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator;

    class Thread extends \Component\Core {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "autoloader" => new Validation(\AUTOLOAD, [new Validator\IsString\IsFile]),
                "model" => new Validation(__DIR__ . \DIRECTORY_SEPARATOR . "Thread.tpl", [new Validator\IsString\IsFile]),
                "thread" => new Validation(false, [new Validator\IsString, new Validator\IsString\IsPath]),
                "callback" => new Validation(false, [new Validator\IsString]),
                "class" => new Validation(false, [new Validator\IsString]),
                "parameters" => new Validation(false, [new Validator\IsString])] + $_parameters);
        }
        
        final public function deploy() {
            if (!\file_exists(($file = (new \Component\Path(\dirname($this->thread)))->getPath() . \DIRECTORY_SEPARATOR . \basename($this->thread)))) {

                $this->parameters = $this->__dry();

                (new \Component\Caller\File\Fopen($file, "w+"))->write($this->replace(\file_get_contents($this->model), ["autoloader", "class", "parameters", "callback"]));
            }
        }
    }

}