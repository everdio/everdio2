<?php
namespace Component\Core\Controller {
    use \Component\Validation, \Component\Validator;
    abstract class Model extends \Component\Core\Controller {    
        abstract public function setup() : void;
        
        public function dispatch(string $path) {   
            
            /*
            if ((isset($this->host) && $this->host === "droomparadijs.nl") || strpos($this->path, "droomparadijs2")) {
                return (string) parent::dispatch($this->getModel($path));
            }
             */
            
            return (string) parent::dispatch($this->getModel($path));
            
        }    
        
        /*
        final public function getModel(string $path, bool $reset = false) : string {
            if (\file_exists(($file = \sprintf("%s/%s.ini", $this->path, $path)))) {
                foreach (\array_merge_recursive(\parse_ini_file($file, true, \INI_SCANNER_TYPED)) as $parameter => $value) {  
                    $this->add($parameter, new \Component\Validation\Parameter($value), $reset);                                     
                }                         
            }           
            return (string) $path;
        }
         * 
         */

        final public function getModel(string $path, bool $reset = false) : string {
            if (\file_exists(($file = \sprintf("%s/%s.ini", $this->path, $path)))) {
                foreach (\array_merge_recursive(\parse_ini_file($file, true, \INI_SCANNER_TYPED)) as $parameter => $value) {  
                    if (\is_array($value)) {
                        $this->add($parameter, new \Component\Validation(new \Component\Core\Parameters, [new \Component\Validator\IsObject]), $reset);
                        $this->{$parameter}->store($value);
                    } else {
                        $this->add($parameter, new \Component\Validation\Parameter($value), $reset);                                                             
                    }
                }                         
            }           
            
            return (string) $path;
        }        
    }
}


