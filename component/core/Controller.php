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
                "regex" => new Validation("!\{\{(.+?)\}\}!", [new Validator\IsString]),
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
                
                return (string) $this->desanitize($this->caller(\ob_get_clean()));
            }
        }        
        
        private function caller(string $output = NULL, array $matches = [], array $arguments = []) {
            if (\is_string($output) && \preg_match_all($this->regex, $output, $matches, \PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $key => $match) {
                    if (\method_exists($this, \parse_url($match, \PHP_URL_SCHEME))) {
                        if (($query = \parse_url(\html_entity_decode($match), \PHP_URL_QUERY))) {
                            \parse_str($query, $arguments);
                        }

                        $output = \str_replace($matches[0][$key], $this->caller(\call_user_func_array([$this, \parse_url($match, \PHP_URL_SCHEME)], \array_values($arguments))), $output);                        
                    }
                }            
            }
            
            return (string) $output;
        }

     
        public function execute(string $path, array $parameters = [], string $extension = "php") {
            $controller = new $this;
            $controller->import($this->export(\array_merge($controller->diff(), $parameters)));
            $controller->path = (!isset($this->path) ? \realpath(\dirname($path)) : \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path)));
            if (isset($controller->path)) {
                return $controller->dispatch(\basename($path), $extension);        
            }
        }
    }    
}