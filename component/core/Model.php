<?php

namespace Component\Core {

    use \Component\Caller\File\Fopen;

    trait Model {

        public function __destruct() {
            $this->parameters = $this->__dry();

            $fopen = new Fopen((new \Component\Path(\strtolower(\implode(\DIRECTORY_SEPARATOR, \explode("\\", $this->namespace)))))->getPath() . \DIRECTORY_SEPARATOR . $this->class . ".php", "w");
            $fopen->write($this->replace(\file_get_contents($this->model), ["namespace", "class", "use", "extends", "parameters"]));
        }
    }

}

