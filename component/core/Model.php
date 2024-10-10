<?php

namespace Component\Core {

    use \Component\Caller\File\Fopen;

    trait Model {

        public function __destruct() {
            
            $file = (new \Component\Path(\strtolower(\implode(\DIRECTORY_SEPARATOR, \explode("\\", $this->namespace)))))->getPath() . \DIRECTORY_SEPARATOR . $this->class . ".php";
            
            if (!\file_exists($file) || $this->overwrite) {
                $this->parameters = $this->__dry();

                (new Fopen($file, "w"))->write($this->replace(\file_get_contents($this->model), ["namespace", "class", "use", "extends", "parameters"]));
            }
        }
    }

}

