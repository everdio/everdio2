<?php
namespace Component\Core\Controller {
    use \Component\Validation, \Component\Validator;
    abstract class Model extends \Component\Core\Controller {    
        public function __construct(array $parameters = []) {
            parent::__construct([
                "parser" => new Validation(false, array(new Validator\IsObject\Of("\Component\Parser")))
            ] + $parameters);
        }
        
        abstract public function setup() : void;        
        
        public function dispatch(string $basename) {   
            $validation = new Validation($this->path . DIRECTORY_SEPARATOR . $basename . "." . $this->parser::EXTENSION, [new Validator\IsString\IsFile]);
            if ($validation->isValid()) {
                $file = new \Component\File($validation->execute(), "r");                   
                foreach (array_merge_recursive($this->parser::parse($file->restore())) as $parameter => $value) {    
                    $this->add($parameter, new Validation($value, [new Validator\IsArray, new Validator\IsString, new Validator\IsNumeric]));
                }                         
            }
            
            return (string) parent::dispatch($basename);
        }       
    }
}

