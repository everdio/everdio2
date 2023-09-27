<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator;

    class Thread extends \Component\Core {

        public function __construct() {
            parent::__construct([
                "model" => new Validation(__DIR__ . \DIRECTORY_SEPARATOR . "Thread.tpl", [new Validator\IsString\IsFile]),
                "thread" => new Validation(false, [new Validator\IsString, new Validator\IsString\IsPath]),
                "extends" => new Validation(false, [new Validator\IsString]),
                "parameters" => new Validation(false, [new Validator\IsString])]);
        }

        public function __destruct() {            
            $this->parameters = $this->__dry();
            
            $fopen = new \Component\Caller\File\Fopen((new \Component\Path(\dirname($this->thread)))->getPath() . \DIRECTORY_SEPARATOR . \basename($this->thread), "w+");
            $fopen->write($this->replace(\file_get_contents($this->model), ["extends", "parameters"]));
        }
    }

}

