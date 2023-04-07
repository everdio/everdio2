<?php
namespace Component\Core\Controller {
    use \Component\Validation, \Component\Validator, \Component\Core\Parameters;
    abstract class Model extends \Component\Core\Controller {    
        /*
         * A required setup function to process the basic server input for the controller
         */
        abstract public function setup() : void;
        
        /*
         * dispatching the Model if exists
         */
        public function dispatch(string $path) {   
            return (string) parent::dispatch($this->getModel($path));
        }    
        
        /*
         * checks if model ini file exists
         */
        final public function hasModel(string $path) : bool {
            return (bool) \file_exists($this->path . \DIRECTORY_SEPARATOR . $path . ".ini");
            
        }        
        
        /*
         * parsing ini contents and set as Parameters container(s)
         */
        final public function getModel(string $path, bool $reset = false) : string {
            if ($this->hasModel($path)) {
                foreach (\array_merge_recursive(\parse_ini_file($this->path . \DIRECTORY_SEPARATOR . $path . ".ini", true, \INI_SCANNER_TYPED)) as $parameter => $value) {  
                    if (\is_array($value)) {
                        $this->add($parameter, new Validation(new Parameters, [new Validator\IsObject]), $reset);
                        $this->{$parameter}->store($value);
                    } else {
                        $this->add($parameter, new Validation\Parameter($value), $reset);                                                             
                    }
                }                         
            }           
            
            return (string) $path;
        }        
    }
}


