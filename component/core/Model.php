<?php

namespace Component\Core {

    use \Component\Caller\File\Fopen;

    trait Model {
        public function __toString(): string {
            return \strtolower(\implode(\DIRECTORY_SEPARATOR, \explode("\\", $this->namespace))) . \DIRECTORY_SEPARATOR . $this->class . ".php";
        }
        
        public function __destruct() {
            $this->parameters = $this->__dry();

            $file = (string) $this;
            
            $fopen = new Fopen((new \Component\Path(\dirname($file)))->getPath() . \DIRECTORY_SEPARATOR . \basename($file), "w+");
            $fopen->write($this->replace(\file_get_contents($this->model), ["namespace", "class", "use", "extends", "parameters"]));
        }
    }

}

