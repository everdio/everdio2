<?php
namespace Components\Core {
    use \Components\Validation;
    use \Components\Validator;
    abstract class Controller extends \Components\Core {            
        public function __construct(array $parameters = []) {
            parent::__construct([                
                "pid" => new Validation(getmypid(), [new Validator\IsInteger]),
                "path" => new Validation(DIRECTORY_SEPARATOR, [new Validator\IsString\IsPath]),
                "arguments" => new Validation(false, [new Validator\IsArray\Sizeof\Bigger(1)]),
                "time" => new Validation(microtime(true), [new Validator\IsFloat]),
                "request" => new Validation(new \Components\Core\Parameters, [new Validator\IsObject\Of("\Components\Core\Parameters")]),
                "token" => new Validation(bin2hex(openssl_random_pseudo_bytes(32)), [new Validator\IsString, new Validator\Len\Bigger(45)])
            ] + $parameters);
        }
        
        final protected function isRouted(string $route) : bool {
            return (bool) (isset($this->arguments) && implode(DIRECTORY_SEPARATOR, array_intersect_assoc(explode(DIRECTORY_SEPARATOR, (string) $route), $this->arguments)) === (string) $route);
        }        
        
        protected function dispatch(string $path) {
            $validation = new Validation($this->path . DIRECTORY_SEPARATOR . $path . ".php", [new Validator\IsString\IsFile]);            
            if ($validation->isValid()) {
                ob_start();
                require $validation->execute();    
                return (string) ob_get_clean();                                                            
            }
        }        
        
        abstract public function display(string $path);

        final protected function execute($path) {  
            $controller = clone $this;
            $controller->path = realpath(dirname($controller->path . DIRECTORY_SEPARATOR . trim($path)));
            
            if (isset($controller->path)) {
                return (string) trim($controller->dispatch(basename($path)));
            }
            
            throw new \LogicException (sprintf("controller failed executing %s", $path));
        }
    }    
}