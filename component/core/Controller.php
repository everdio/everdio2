<?php
namespace Component\Core {
    use \Component\Validation, \Component\Validator;
    abstract class Controller extends \Component\Core {            
        public function __construct(array $_parameters = []) {
            parent::__construct([                
                "pid" => new Validation(\getmypid(), [new Validator\IsInteger]),
                "time" => new Validation(\microtime(true), [new Validator\IsFloat]),
                "token" => new Validation(\bin2hex(\openssl_random_pseudo_bytes(32)), [new Validator\IsString, new Validator\Len\Bigger(45)]),
                "path" => new Validation(false, [new Validator\IsString\IsPath\IsReal]),
                "arguments" => new Validation(false, [new Validator\IsArray\Sizeof\Bigger(1)]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject\Of("\Component\Core\Parameters")])
            ] + $_parameters);
        }        
        
        final public function isRoute(string $route) : bool {
            return (bool) (isset($this->arguments) && \implode(\DIRECTORY_SEPARATOR, \array_intersect_assoc(\explode(\DIRECTORY_SEPARATOR, (string) $route), $this->arguments)) === (string) $route);
        }        

        public function dispatch(string $path, string $extension) {
            $validation = new Validation($this->path . \DIRECTORY_SEPARATOR . $path . "." . $extension, [new Validator\IsString\IsFile]);
            if ($validation->isValid()) {
                \ob_start();
                require $validation->execute();   
                return (string) \ob_get_clean();                                                     
            }
        }        
        
        private function parse_object(string $querystring) : string {
            return (string) \parse_url($querystring, \PHP_URL_HOST);            
        }        
        
        private function parse_method(string $querystring) : string {
            return (string) \parse_url($querystring, \PHP_URL_SCHEME);
        }
        
        private function parse_arguments(string $querystring, array $arguments = []) : array {
            if ($query = \parse_url(\html_entity_decode($querystring), \PHP_URL_QUERY)) {
                \parse_str($query, $arguments);
            }
            
            return (array) $arguments;            
        }                     
        
        final public function parse(string $output = NULL, string $replace = "{{%s}}", string $regex = "!\{\{(.+?)\}\}!", array $matches = []) {
            if (\is_string($output) && \preg_match_all($regex, $output, $matches, \PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $match) {
                    if (($object = $this->parse_object($match)) && ($method = $this->parse_method($match)) && \method_exists($object, $method)) {                        
                        $output = \str_replace(sprintf($replace, $match), $this->parse($this->call($match), $replace, $regex), $output);                        
                    }
                }            
            }
            
            return (string) $output;
        } 
        
        final public function call(string $querystring) {
            if (\is_a($this, $this->parse_object($querystring))) {
                return \call_user_func_array([$this, $this->parse_method($querystring)], \array_values($this->parse_arguments($querystring)));
            }
        }         

        public function execute(string $path, array $parameters = [], string $extension = "php") {
            $controller = new $this;
            $controller->import($this->export(\array_merge($controller->diff(), $parameters)));
            $controller->path = (!isset($this->path) ? \realpath(\dirname($path)) : \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path)));
            if (isset($controller->path)) {
                return $this->desanitize($controller->parse($controller->dispatch(\basename($path), $extension)));        
            }
        }
    }    
}