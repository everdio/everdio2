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
        public function dispatch(string $path) : string {   
            return (string) parent::dispatch($this->getModel($path));
        }    
        
        /*
         * checks if model ini file exists
         */
        final public function hasModel(string $path) : bool {
            return (bool) \file_exists($this->path . \DIRECTORY_SEPARATOR . $path . ".ini");
        }        
        
        /*
         * creating and (re)storing an ini file, directory should exist
         */
        final public function putModel(string $path, array $parameters) {
            $ini = new \Component\Caller\File\Fopen\Ini($this->path . \DIRECTORY_SEPARATOR .  $path, "w");
            foreach ($this->restore($parameters) as $section => $data) {
                $ini->store($section, $data->restore());
            }            
        }
        
        /*
         * parsing ini contents and set as Parameters container(s)
         */
        final public function getModel(string $path, bool $reset = false) : string {
            if ($this->hasModel($path)) {
                foreach (\array_merge_recursive(\parse_ini_file($this->path . \DIRECTORY_SEPARATOR . $path . ".ini", true, \INI_SCANNER_TYPED)) as $parameter => $value) {  
                    if (!\in_array($parameter, $this->reserved)) {
                        if (\is_array($value)) {
                            $this->addParameter($parameter, new Validation(new Parameters, [new Validator\IsObject]), $reset);
                            $this->{$parameter}->store($value);
                        } else {
                            $this->addParameter($parameter, new Validation\Parameter($value), $reset);                                                             
                        }
                    } 
                }                         
            }           
            
            return (string) $path;
        }        
    }
}


