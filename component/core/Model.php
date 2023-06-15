<?php
namespace Component\Core {
    use \Component\Caller\File\Fopen;
    trait Model {        
        public function __toString() : string {
            return (string) $this->namespace . "\\" . $this->class;
        }

        public function __destruct() {
            $this->mapper = $this->__dry();
            
            $path = new \Component\Path(\strtolower(\implode(\DIRECTORY_SEPARATOR, \explode("\\", $this->namespace))));
            
            $mapper = new Fopen($path->getPath() . \DIRECTORY_SEPARATOR . $this->class . ".php", "w+");
            $mapper->write($this->replace(\file_get_contents($this->model), ["namespace", "class", "use", "mapper"]));
        }        
    }
}

