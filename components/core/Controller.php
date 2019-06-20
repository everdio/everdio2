<?php
namespace Components\Core {
    use \Components\Validation;
    use \Components\Validator;
    abstract class Controller extends \Components\Core {
        public function __construct() {
            $this->add("time", new Validation(microtime(true), array(new Validator\IsFloat)));
            $this->add("memory", new Validation(memory_get_usage(), array(new Validator\IsInteger)));
            $this->add("root", new Validation(false, array(new Validator\IsString\IsPath)));
            $this->add("path", new Validation(false, array(new Validator\IsString\IsPath, new Validator\IsEmpty)));
            $this->add("arguments", new Validation(false, array(new Validator\IsArray\Sizeof\Bigger(1))));
            $this->add("request", new Validation(false, array(new Validator\IsArray)));
            $this->add("token", new Validation(bin2hex(openssl_random_pseudo_bytes(32)), array(new Validator\IsString), new Validator\Len\Bigger(45)));
        }

        public function isRequested(array $request = []) {
            if (isset($this->request)) {
                $validation = new Validation($request, array(new Validator\IsArray\Intersect(array_keys($this->request))));
                return (bool) $validation->validate();
            }
        }
        
        protected function isRouted(string $route = NULL) : bool {
            return (bool) (!isset($this->arguments) || (isset($this->arguments) && implode(DIRECTORY_SEPARATOR, array_intersect_assoc(explode(DIRECTORY_SEPARATOR, (string) $route), $this->arguments)) === (string) $route));
        }        
        
        protected function dispatch($route) {
            $validation = new Validation($this->path . DIRECTORY_SEPARATOR . $route . ".php", array(new Validator\IsString\IsFile));            
            if ($validation->validate()) {
                ob_start();
                require_once $validation->execute();
                return (string) ob_get_clean();                                                            
            }
        }        

        public function execute($path, string $route = NULL) {  
            if ($this->isRouted($route)) {
                $validation = new Validation(dirname($this->path . DIRECTORY_SEPARATOR . $path), array(new Validator\IsString\IsDir));
                $controller = clone $this;
                $controller->path = $validation->execute();    
                return (string) trim($controller->dispatch(basename($path)));                                                        
            }
        }
    }    
}