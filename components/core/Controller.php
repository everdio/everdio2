<?php
namespace Components\Core {
    use \Components\Validation;
    use \Components\Validator;
    abstract class Controller extends \Components\Core {
        public function __construct() {
            $this->add("time", new Validation(microtime(true), [new Validator\IsFloat]));
            $this->add("memory", new Validation(memory_get_usage(true), [new Validator\IsInteger]));
            $this->add("root", new Validation(false, [new Validator\IsString\IsPath]));
            $this->add("path", new Validation(false, [new Validator\IsString\IsPath]));
            $this->add("dispatch", new Validation(false, [new Validator\IsString\IsPath]));
            $this->add("arguments", new Validation(false, [new Validator\IsArray\Sizeof\Bigger(1)]));
            $this->add("request", new Validation(false, [new Validator\IsArray]));
            $this->add("token", new Validation(bin2hex(openssl_random_pseudo_bytes(32)), [new Validator\IsString, new Validator\Len\Bigger(45)]));
        }

        final public function isRequested(array $request = []) {
            if (isset($this->request)) {
                $validation = new Validation($request, [new Validator\IsArray\Intersect(array_keys($this->request))]);
                return (bool) $validation->isValid();
            }
        }
        
        final protected function isRouted(string $route = NULL) : bool {
            return (bool) (!isset($this->arguments) || (isset($this->arguments) && implode(DIRECTORY_SEPARATOR, array_intersect_assoc(explode(DIRECTORY_SEPARATOR, (string) $route), $this->arguments)) === (string) $route));
        }        
        
        protected function dispatch() {
            $validation = new Validation($this->dispatch . ".php", [new Validator\IsString\IsFile]);            
            if ($validation->isValid()) {
                ob_start();
                require_once $validation->execute();
                return (string) ob_get_clean();                                                            
            }
            
            throw new Event(sprintf("unknown dispatch %s", $validation->get()));
        }        

        public function execute($path, string $route = NULL) {  
            if ($this->isRouted($route)) {                
                $this->path = dirname($this->path . DIRECTORY_SEPARATOR . $path);
                $this->dispatch = $this->path . DIRECTORY_SEPARATOR . basename($path);
                if (!in_array(false, $this->validate(["root", "path", "token"]))) {
                    return (string) trim($this->dispatch());
                } 
                 
                throw new Event(sprintf("Controller failure...%s", $this->dehydrate($this->validate())));
            }
        }
    }    
}