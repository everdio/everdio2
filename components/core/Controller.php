<?php
namespace Components\Core {
    use \Components\Validation;
    use \Components\Validator;
    abstract class Controller extends \Components\Core {        
        const ROOT = "root";
        const PATH = "path";
        const ARGUMENTS = "arguments";
        const REQUEST = "request";
        const INPUT = "input";
        public function __construct() {
            $this->add(self::ROOT, new Validation(false, [new Validator\IsString\IsPath]));
            $this->add(self::PATH, new Validation(false, [new Validator\IsString\IsPath]));
            $this->add(self::ARGUMENTS, new Validation(false, [new Validator\IsArray\Sizeof\Bigger(1)]));
            $this->add(self::REQUEST, new Validation(false, [new Validator\IsArray\Sizeof\Bigger(1)]));
            $this->add(self::INPUT, new Validation(new \Components\Core\Request, [new Validator\IsObject\Of("\Components\Core\Request")]));
        }
        
        final public function hasRequest(string $request) : bool {
            return (bool) (isset($this->request) && array_key_exists($request, $this->request) && !empty($this->request[$request]));
        }
        
        final public function getRequest(string $request) {
            if ($this->hasRequest($request)) {
                return $this->request[$request];
            }
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
        
        protected function dispatch(string $path) {
            $validation = new Validation($this->path . DIRECTORY_SEPARATOR . $path . ".php", [new Validator\IsString\IsFile]);            
            if ($validation->isValid()) {
                ob_start();
                require_once $validation->execute();
                return (string) ob_get_clean();                                                            
            }
        }        
        
        final public function time(int $decimals = 4) : float {
            return (float) round(microtime(true) - $this->time, $decimals);
        }

        public function execute($path, string $route = NULL) {  
            if ($this->isRouted($route)) {     
                $controller = clone $this;
                $controller->path = dirname($controller->path . DIRECTORY_SEPARATOR . $path);
                if (!in_array(false, $controller->validate([self::ROOT, self::PATH]))) {
                    return (string) trim($controller->dispatch(basename($path)));
                } 
                 
                throw new Event(sprintf("Controller failure...%s", $this->dehydrate($controller->validate())));
            }
        }
    }    
}