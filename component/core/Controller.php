<?php
namespace Component\Core {
    use \Component\Validation;
    use \Component\Validator;
    abstract class Controller extends \Component\Core {            
        public function __construct(array $parameters = []) {
            parent::__construct([                
                "pid" => new Validation(getmypid(), [new Validator\IsInteger]),
                "path" => new Validation(DIRECTORY_SEPARATOR, [new Validator\IsString\IsPath]),
                "arguments" => new Validation(false, [new Validator\IsArray\Sizeof\Bigger(1)]),
                "time" => new Validation(microtime(true), [new Validator\IsFloat]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject\Of("\Component\Core\Parameters")]),
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

        final protected function execute($path) {  
            $controller = clone $this;
            $controller->path = realpath(dirname($controller->path . DIRECTORY_SEPARATOR . trim($path)));
            if (isset($controller->path)) {
                try {
                    return (string) trim($controller->dispatch(basename($path)));    
                } catch (\InvalidArgumentException $ex) {
                    return (string) sprintf("input/output; %s\n%s", $ex->getMessage(), $ex->getTraceAsString());
                } catch (\RuntimeException $ex) {
                    return (string) sprintf("execute; %s", $ex->getMessage());
                }
            }
            
            throw new \LogicException (sprintf("failed executing %s", $path));
        }
        
        abstract public function display(string $path);        
    }    
}