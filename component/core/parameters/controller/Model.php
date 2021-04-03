<?php
namespace Component\Core\Parameters\Controller {
    use \Component\Validation, \Component\Validator;
    abstract class Model extends \Component\Core\Parameters\Controller {    
        public function __construct(\Component\Parser $parser) {
            parent::__construct();
            $this->parser = $parser;
        }
        
        final protected function dispatch(string $path) {   
            $validation = new Validation($this->path . DIRECTORY_SEPARATOR . $path . $this->parser::EXTENSION, array(new Validator\IsString\IsFile));
            if ($validation->isValid()) {
                $file = new \Component\File($validation->execute(), "r");                   
                foreach (array_merge_recursive($this->parser::parse($file->restore())) as $parameter => $value) {    
                    $this->{(string) $parameter} = $value;
                }                         
                
            }
            return (string) parent::dispatch($path);
        }       
    }
}


