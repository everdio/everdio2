<?php
namespace Components\Core\Adapter {
    use \Components\File;        
    use \Components\Validation;
    use \Components\Validator;    
    abstract class Model extends \Components\Core\Adapter {
        use \Components\Dryer;
        public function __construct($key) {
            parent::__construct($key);            
            $this->add("model", new Validation(sprintf("%s/Model.tpl", __DIR__), [new Validator\IsString\IsFile]));
            $this->add("root", new Validation(false, [new Validator\IsString\IsPath]));
            $this->add("namespace", new Validation(false, [new Validator\IsString]));
            $this->add("class", new Validation(false, [new Validator\IsString]));
            $this->add("extends", new Validation(false, [new Validator\IsString]));
        }        
        
        public function __dry() : string {
            $path = new \Components\Path($this->root . DIRECTORY_SEPARATOR . strtolower(implode(DIRECTORY_SEPARATOR, explode("\\", $this->namespace))));
            $file = new File($path->getPath() . DIRECTORY_SEPARATOR . $this->class . ".php", "w+");                                    
            $model = new File($this->model);

            $tpl = new \Components\Core\Template;    
            $tpl->namespace = $this->namespace;
            $tpl->class = $this->class;
            $tpl->extends = $this->extends;
            $tpl->key = $this->key;

            $this->remove("model");
            $this->remove("namespace");
            $this->remove("class");
            $this->remove("extends");
            $this->remove("root");
            $this->remove("instance");
            $this->remove("key");
            
            $validations = false;
            foreach ($this->parameters() as $parameter) {
                $validations .= sprintf("\$this->add(\"%s\", %s);", $parameter, $this->get($parameter)->__dry()) . PHP_EOL;
            }
            
            $tpl->model = $validations;
            
            $file->store($tpl->display($model->restore()));
            
            return (string) $file->getPath() . DIRECTORY_SEPARATOR . $file->getBasename();
        }        
    }
}