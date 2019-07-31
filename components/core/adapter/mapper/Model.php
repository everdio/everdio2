<?php
namespace Components\Core\Adapter\Mapper {
    use \Components\Validation;
    use \Components\Validator;    
    use \Components\Path;
    use \Components\File;    
    abstract class Model extends \Components\Core\Adapter\Mapper {
        public function __construct(\Components\Core\Adapter\Constructor $constructor) {
            $this->add("instance", new Validation($constructor, [new Validator\IsObject]));            
            $this->add("mapping", new Validation(false, [new Validator\IsArray]));                        
            $this->add("model", new Validation(sprintf("%s/Model.tpl", __DIR__), [new Validator\IsString\IsFile]));
            $this->add("path", new Validation(false, [new Validator\IsString\IsPath]));
            $this->add("namespace", new Validation(false, [new Validator\IsString]));
            $this->add("class", new Validation(false, [new Validator\IsString]));
            $this->add("extends", new Validation(false, [new Validator\IsString]));
        }
        
        abstract public function setup();

        public function __destruct() {
            $path = new Path($this->path . DIRECTORY_SEPARATOR . strtolower(implode(DIRECTORY_SEPARATOR, explode("\\", $this->namespace))));
            $file = new File($path->getPath() . DIRECTORY_SEPARATOR . $this->class . ".php", "w+");
            $model = new \Components\File($this->model);

            $tpl = new \Components\Core\Template;    
            $tpl->namespace = $this->namespace;
            $tpl->class = $this->class;
            $tpl->extends = $this->extends;

            $this->remove("model");
            $this->remove("path");
            $this->remove("namespace");
            $this->remove("class");
            $this->remove("extends");

            $tpl->model = $this->__dry();
            $file->store($tpl->display($model->restore()));            
        }        
    }
}