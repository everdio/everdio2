<?php
namespace Components\Core {
    use \Components\Validation;
    use \Components\Validator;
    abstract class Controller extends \Components\Core {        
        public function __construct(array $parameters = []) {
            parent::__construct([                
                "path"=> new Validation(DIRECTORY_SEPARATOR, [new Validator\IsString\IsPath]),
                "arguments" => new Validation(false, [new Validator\IsArray\Sizeof\Bigger(1)]),
                "request" => new Validation(false, [new Validator\IsArray\Sizeof\Bigger(1)]),
                "input" => new Validation(new \Components\Core\Parameters, [new Validator\IsObject\Of("\Components\Core\Parameters")])                
            ] + $parameters);
        }
        
        final protected function isRouted(string $route = NULL) : bool {
            return (bool) (!isset($this->arguments) || (isset($this->arguments) && implode(DIRECTORY_SEPARATOR, array_intersect_assoc(explode(DIRECTORY_SEPARATOR, (string) $route), $this->arguments)) === (string) $route));
        }        
        
        protected function dispatch(string $path) {
            $validation = new Validation($this->path . DIRECTORY_SEPARATOR . $path . ".php", [new Validator\IsString\IsFile]);            
            if ($validation->isValid()) {
                ob_start();
                require $validation->execute();
                return (string) ob_get_clean();                                                            
            }
        }        

        final public function execute($path, string $route = NULL) {  
            if ($this->isRouted($route)) {     
                $controller = clone $this;
                $controller->path = dirname($controller->path . $path);
                if (!in_array(false, $controller->validate(["path"]))) {
                    return (string) trim($controller->dispatch(basename($path)));
                } 
                 
                throw new Event(sprintf("Controller failure... %s", $controller->validate()));
            }
        }
    }    
}