<?php
namespace Component\Core {
    use \Component\Validation, \Component\Validator;
    abstract class Controller extends \Component\Core {            
        public function __construct(array $_parameters = []) {
            parent::__construct([                
                "token" => new Validation(\bin2hex(\openssl_random_pseudo_bytes(32)), [new Validator\IsString, new Validator\Len\Bigger(45)]),
                "path" => new Validation(false, [new Validator\IsString\IsPath\IsReal]),
                "arguments" => new Validation(false, [new Validator\IsArray]),
                "regex" => new Validation("!\{\{(.+?)\}\}!", [new Validator\IsString]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject\Of("\Component\Core\Parameters")])
            ] + $_parameters);
        }        
        
        final public function isRoute(string $route) : bool {
            return (bool) (isset($this->arguments) && \implode(\DIRECTORY_SEPARATOR, \array_intersect_assoc(\explode(\DIRECTORY_SEPARATOR, (string) $route), $this->arguments)) === (string) $route);
        }        
        
        final public function getController(string $path) {
            if (\file_exists(($file = \sprintf("%s/%s.php", $this->path, $path)))) {
                \ob_start();
                require $file;
                return (string) $this->caller(\ob_get_clean());
            }            
        }

        public function dispatch(string $path) {
            return (string) $this->getController($path);
        }        
        
        final public function call(string $querystring, array $arguments = []) {
            if (($scheme = \parse_url($querystring, \PHP_URL_SCHEME)) && \method_exists($this, $scheme)) {
                if (($query = \parse_url(\html_entity_decode($querystring), \PHP_URL_QUERY))) {
                    \parse_str($query, $arguments);
                }
                
                return \call_user_func_array([$this, $scheme], \array_values($arguments));
            }    
        }
        
        final public function caller(string $output, array $matches = []) {
            if (\is_string($output) && \preg_match_all($this->regex, $output, $matches, \PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $key => $match) {
                    if (!\is_string($data = $this->call($match))) {
                        $data = $this->dehydrate($data);
                    }                 
                    
                    $output = \str_replace($matches[0][$key], $data, $output);
                }            
            }
            
            return (string) $output;
        }

     
        public function execute(string $path, array $parameters = []) {
            $controller = new $this;
            $controller->import($this->export(\array_merge($controller->diff(), $parameters)));
            $controller->path = (!isset($this->path) ? \realpath(\dirname($path)) : \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path)));
            if (isset($controller->path)) {
                return $controller->dispatch(\basename($path));        
            }
        }
    }    
}