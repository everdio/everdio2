<?php

namespace Component\Core {

    trait Model {

        public function deploy(): void {
            if (!\file_exists(($file = (new \Component\Path(\strtolower(\implode(\DIRECTORY_SEPARATOR, \explode("\\", $this->namespace)))))->getPath() . \DIRECTORY_SEPARATOR . $this->class . ".php")) || $this->overwrite) {

                $this->parameters = $this->__dry();

                (new \Component\Caller\File\Fopen($file, "w"))->write($this->replace(\file_get_contents($this->model), ["namespace", "class", "use", "extends", "parameters"]));
            }
        }
    }

}

