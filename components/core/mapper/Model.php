<?php
namespace Components\Core\Mapper {
    use \Components\File;        
    use \Components\Validation;
    use \Components\Validator;    
    abstract class Model extends \Components\Core\Mapper {
        public function __construct(array $parameters = []) {
            parent::__construct([
                "label" => new Validation(false, [new Validator\IsString]),                
                "model" => new Validation(__DIR__ . DIRECTORY_SEPARATOR . "Model.tpl", [new Validator\IsString\IsFile]),
                "namespace" => new Validation(false, [new Validator\IsString]),
                "class" => new Validation(false, [new Validator\IsString]),
                "use" => new Validation(false, [new Validator\IsString])
            ] + $parameters);
        }                
        
        abstract public function setup();

        public function __destruct() {
            $path = new \Components\Path(strtolower(implode(DIRECTORY_SEPARATOR, explode("\\", $this->namespace))));
            $model = new File($this->model);                
            $file = new File($path->getPath() . DIRECTORY_SEPARATOR . $this->class . ".php", "w+");                                                    
            $tpl = new \Components\Core\Template;    
            $tpl->namespace = $this->namespace;
            $tpl->class = $this->class;
            $tpl->use = $this->use;            
            $this->remove("model");
            $this->remove("namespace");
            $this->remove("class");
            $this->remove("use");
            $tpl->mapper = $this->__dry();
            $file->store($tpl->display($model->restore()));
        }
    }
}