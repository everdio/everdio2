<?php
namespace Components\Core {
    use \Components\Path;
    use \Components\File;        
    use \Components\Validation;
    use \Components\Validator;
    class Library extends \Components\Core {   
        public function __construct($resource) {
            $this->add("id", new Validation(false, array(new Validator\IsString)));                    
            $this->add("resource", new Validation($resource, array(new Validator\IsObject)));   
            $this->add("namespace", new Validation(false, array(new Validator\IsString)));
            $this->add("extend", new Validation(false, array(new Validator\IsString)));
            $this->add("root", new Validation(false, array(new Validator\IsString\IsDir)));            
            $this->add("model", new Validation(__DIR__ . DIRECTORY_SEPARATOR . "Library.tpl", array(new Validator\IsString\IsFile)));            
        }
        
        public function __dry() : string {
            $file = new File($this->model);
            
            $index = new \Components\Index($this->id);
            $index->store($this->resource);
            
            $tpl = new \Components\Core\Template;            
            $tpl->namespace = $this->namespace;
            $tpl->class = $this->labelize($this->id);
            $tpl->extend = $this->extend;
            $tpl->index = $index->__dry();
            return (string) $tpl->display($file->restore());                
        }
        
        public function getNamespace() : string {
            return (string) sprintf("%s\%s", $this->namespace, $this->labelize($this->id));
        }
        
        public function getExtend() : string {
            return (string) sprintf("\%s", $this->getNamespace());
        }
        
        public function create() {
            $library = new Path($this->root . DIRECTORY_SEPARATOR . strtolower(implode(DIRECTORY_SEPARATOR, explode("\\", $this->namespace))));            
            $file = new File($library->getPath() . DIRECTORY_SEPARATOR . $this->labelize($this->id) . ".php", "w+");
            $file->store($this->__dry());
        } 
    }
}