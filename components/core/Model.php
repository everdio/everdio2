<?php
namespace Components\Core {
    use \Components\File;        
    trait Model {        
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

