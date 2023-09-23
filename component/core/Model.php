<?php

namespace Component\Core {

    use \Component\Caller\File\Fopen;

    trait Model {

        public function __toString(): string {
            return \strtolower(\implode(\DIRECTORY_SEPARATOR, \explode("\\", $this->namespace))) . \DIRECTORY_SEPARATOR . $this->class . ".php";
        }

        public function __destruct() {
            
            $this->parameters = $this->__dry();
            
            $file = new Fopen((new \Component\Path(\dirname((string) $this)))->getPath() . \DIRECTORY_SEPARATOR . \basename((string) $this), "w+");
            $file->write($this->replace(\file_get_contents($this->model), ["namespace", "class", "use", "extends", "parameters"]));
        }
    }

}

