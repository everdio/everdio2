<?php
namespace Components\Core\Mapping {
    use \Components\Path;
    use \Components\File;        
    use \Components\Validation;
    use \Components\Validator;
    
    abstract class Library extends \Components\Core\Mapping {   
        public function __construct(\Components\Core\Mapping\Instance $instance) {
            parent::__construct($instance);
            $this->add("class", new Validation(false, [new Validator\IsString]));
            $this->add("namespace", new Validation(false, [new Validator\IsString]));
            $this->add("extend", new Validation(false, [new Validator\IsString]));
            $this->add("root", new Validation(false, [new Validator\IsString\IsDir]));
            $this->add("model", new Validation(__DIR__ . DIRECTORY_SEPARATOR . "Library.tpl", [new Validator\IsString\IsFile]));
            $this->add("mappers", new Validation(false, [new Validator\IsArray]));
        }        
                
        public function getNamespace() : string {
            return (string) sprintf("%s\%s", $this->namespace, $this->class);
        }
        
        public function getExtend() : string {
            return (string) sprintf("\%s", $this->getNamespace());
        }        
        
        abstract public function setup();
        
        public function __destruct() {
            $library = new Path($this->root . DIRECTORY_SEPARATOR . strtolower(implode(DIRECTORY_SEPARATOR, explode("\\", $this->namespace))));
            $mapper = new File($library->getPath() . DIRECTORY_SEPARATOR . $this->class . ".php", "w+");
            $model = new File($this->model);
            
            $tpl = new \Components\Core\Template;
            $tpl->namespace = $this->namespace;
            $tpl->class = $this->class;
            $tpl->extend = $this->extend;

            $index = new \Components\Index($this->class);
            $index->store($this->instance);            
            $tpl->index = $index->__dry();
            
            $this->remove("model");
            $this->remove("namespace");
            $this->remove("instance");
            $this->remove("extend");
            $this->remove("root");
                    
            $tpl->library = $this->__dry();        
            $mapper->store($tpl->display($model->restore()));
        }       
    }
}