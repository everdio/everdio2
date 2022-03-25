<?php
namespace Component\Core\Controller {
    use \Component\Validation, \Component\Validator;
    abstract class Model extends \Component\Core\Controller {    
        abstract public function setup() : void;
        
        public function dispatch(string $path, string $extension,) {   
            $validation = new Validation(sprintf("%s/%s.ini", $this->path, $path), [new Validator\IsString\IsFile]);
            if ($validation->isValid()) {
                foreach (\array_merge_recursive(\parse_ini_file($validation->execute(), true, \INI_SCANNER_TYPED)) as $parameter => $value) {    
                    $this->add($parameter, new Validation($value, [new Validator\IsArray, new Validator\IsString, new Validator\IsNumeric]));
                }                         
            }
            
            return (string) parent::dispatch($path, $extension);
        }       
    }
}


