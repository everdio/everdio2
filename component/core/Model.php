<?php
namespace Component\Core {
    use \Component\Caller\File\Fopen;
    trait Model {        
        public function __toString() {
            return (string) $this->namespace . "\\" . $this->class;
        }
        
        protected function getPath() : string {
            return (string) \strtolower(\implode(\DIRECTORY_SEPARATOR, \explode("\\", $this->namespace)));
        }
        
        protected function getMapper() : string {
            return (string) \sprintf("%s/%s.php", $this->getPath(), $this->class);
        }
        
        public function __destruct() {
            $path = new \Component\Path($this->getPath());
            
            $model = new Fopen($this->model, "r");
  
            $mapper = new Fopen($this->getMapper(), "w+");
            $template = $this->replace($model->read(\filesize($model->getPath())), ["namespace", "class", "use"], 3);

            $this->remove("model");
            $this->remove("namespace");
            $this->remove("class");
            $this->remove("use");
            
            $this->mapper = $this->__dry();
            $mapper->write($this->replace($template, ["mapper"]));
        }        
    }
}

