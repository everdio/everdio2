<?php
namespace Component\Core\Controller {
    use \Component\Validation, \Component\Validator;
    abstract class Model extends \Component\Core\Controller {    
        abstract public function setup() : void;
        
        public function dispatch(string $path) {   
            if (\file_exists(($file = \sprintf("%s/%s.ini", $this->path, $path)))) {
                foreach (\array_merge_recursive(\parse_ini_file($file, true, \INI_SCANNER_TYPED)) as $parameter => $value) {  
                    $this->add($parameter, new \Component\Validation\Parameter($value));
                    //$this->add($parameter, new Validation($value, [new Validator\IsArray, new Validator\IsString, new Validator\IsNumeric, new Validator\IsObject]));
                }                         
            }
            
            return (string) parent::dispatch($path);
        }       
    }
}


