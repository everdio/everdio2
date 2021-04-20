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
        
        public function dispatch(string $basename) {
            $validation = new Validation($this->path . DIRECTORY_SEPARATOR . $basename . ".php", [new Validator\IsString\IsFile]);            
            if ($validation->isValid()) {
                ob_start();
                require $validation->execute();    
                return (string) ob_get_clean();                                                            
            }
        }
        
        abstract public function prepare();        

        public function execute(string $path, array $parameters = []) {  
            $controller = new $this;
            $controller->import($this->export(array_merge($controller->diff(), $parameters)));
            $controller->path = realpath($this->path . DIRECTORY_SEPARATOR . dirname($path));
            if (isset($controller->path)) {
                try {
                    return $controller->dispatch(basename($path));    
                } catch (\InvalidArgumentException $ex) {
                    throw $ex;
                } catch (\RuntimeException $ex) {
                    throw $ex;
                } catch (\LogicException $ex) {
                    throw $ex;
                }
            }
        }
    }    
}