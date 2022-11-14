<?php
namespace Component\Core {
    use \Component\Caller\File\Fopen;
    trait Model {        
        public function __toString() {
            return (string) $this->namespace . "\\" . $this->class;
        }

        public function __destruct() {
            
            $this->mapper = $this->__dry();
            
            $path = new \Component\Path(\strtolower(\implode(\DIRECTORY_SEPARATOR, \explode("\\", $this->namespace))));
            
            $mapper = new Fopen(\sprintf("%s/%s.php", $path->getPath(), $this->class), "w+");
            $mapper->write($this->replace(\file_get_contents($this->model), ["namespace", "class", "use", "mapper"]));
        }        
    }
}

