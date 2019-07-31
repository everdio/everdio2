<?php
namespace Components\Core\Mapping {
    use \Components\Path;
    use \Components\File;    
    use \Components\Validation;
    use \Components\Validator;
  
    abstract class Model extends \Components\Core\Mapping {     
        public function __construct(\Components\Core\Mapping\Instance $instance) {
            parent::__construct($instance);
            $this->add("model", new Validation(__DIR__ . DIRECTORY_SEPARATOR . "Model.tpl", array(new Validator\IsString\IsFile)));
            $this->add("namespace", new Validation(false, array(new Validator\IsString)));
            $this->add("extend", new Validation(false, array(new Validator\IsString)));
            $this->add("root", new Validation(false, array(new Validator\IsString\IsDir)));
        }

        abstract public function setup();
       
        public function __destruct() {
            $path = new Path($this->root . DIRECTORY_SEPARATOR . strtolower(implode(DIRECTORY_SEPARATOR, explode("\\", $this->namespace))));
            $file = new File($path->getPath() . DIRECTORY_SEPARATOR . $this->mapper . ".php", "w+");
            $model = new \Components\File($this->model);
            
            $tpl = new \Components\Core\Template;            
            $tpl->namespace = $this->namespace;
            $tpl->class = $this->mapper;
            $tpl->extend = $this->extend;
            
            $this->remove("instance");
            $this->remove("model");
            $this->remove("namespace");
            $this->remove("class");
            $this->remove("extend");
            $this->remove("root");
            $this->remove("mapper");

            $tpl->model = parent::__dry();            
            
            $file->store($tpl->display($model->restore()));
        }          
    }
}

