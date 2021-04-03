<?php
namespace Component\Core\Parameters {
    use \Component\Validation;
    use \Component\Validator;
    abstract class Controller extends \Component\Core\Parameters {            
        public function __construct() {
            $this->pid = getmypid();
            $this->path = \DIRECTORY_SEPARATOR;
            $this->time = microtime(true);
            $this->request = new \Component\Core\Parameters;
            $this->token = bin2hex(openssl_random_pseudo_bytes(32));
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

        final public function execute($path) {  
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
    }    
}