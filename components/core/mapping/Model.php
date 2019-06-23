<?php
namespace Components\Core\Mapping {
    use \Components\Path;
    use \Components\File;    
    use \Components\Validation;
    use \Components\Validator;
  
    abstract class Model extends \Components\Core\Mapping {     
        public function __construct($adapter) {
            parent::__construct($adapter);
            $this->add("mapper", new Validation(false, array(new Validator\IsString)));
            $this->add("model", new Validation(__DIR__ . DIRECTORY_SEPARATOR . "Model.tpl", array(new Validator\IsString\IsFile)));
            $this->add("namespace", new Validation(false, array(new Validator\IsString)));
            $this->add("extend", new Validation(false, array(new Validator\IsString)));
            $this->add("root", new Validation(false, array(new Validator\IsString\IsDir)));
        }

        abstract public function setup();
       
        public function __dry() : string {
            $file = new \Components\File($this->model);
            
            $tpl = new \Components\Core\Template;            
            $tpl->namespace = $this->namespace;
            $tpl->class = $this->mapper;
            $tpl->extend = $this->extend;
            
            $this->remove("adapter");
            $this->remove("model");
            $this->remove("namespace");
            $this->remove("class");
            $this->remove("extend");
            $this->remove("root");
            $this->remove("mapper");
            
            $tpl->model = parent::__dry();
            return (string) $tpl->display($file->restore());                
        }        
        
        public function create() {
            $path = new Path($this->root . DIRECTORY_SEPARATOR . strtolower(implode(DIRECTORY_SEPARATOR, explode("\\", $this->namespace))));            
            $file = new File($path->getPath() . DIRECTORY_SEPARATOR . $this->mapper . ".php", "w+");
            $file->store($this->__dry());
        }          
    }
}

