<?php
namespace Component\Core {
    use \Component\File;        
    trait Model {        
        public function __toString() {
            return (string) $this->namespace . "\\" . $this->class;
        }
        
        public function __destruct() {
            $path = new \Component\Path(\strtolower(\implode(\DIRECTORY_SEPARATOR, \explode("\\", $this->namespace))));
            $model = new File($this->model);                
            $file = new File($path->getPath() . \DIRECTORY_SEPARATOR . $this->class . ".php", "w+");                                                    
            
            $template = $this->replace($model->restore(), ["namespace", "class", "use"], 3);
            
            $this->remove("model");
            $this->remove("namespace");
            $this->remove("class");
            $this->remove("use");
            
            $this->mapper = $this->__dry();
            
            $file->store($this->replace($template, ["mapper"]));
        }        
    }
}

