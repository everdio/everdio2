<?php
namespace Component\Core {
    use \Component\Validation, \Component\Validator;
    abstract class Controller extends \Component\Core {            
        public function __construct(array $_parameters = []) {
            parent::__construct([                
                "token" => new Validation(\bin2hex(\openssl_random_pseudo_bytes(32)), [new Validator\IsString, new Validator\Len\Bigger(45)]),
                "path" => new Validation(false, [new Validator\IsString\IsPath\IsReal]),
                "arguments" => new Validation(false, [new Validator\IsString]),
                "regex" => new Validation("!\{\{(.+?)\}\}!", [new Validator\IsString])
            ] + $_parameters);
        }        
        
        final public function isRoute(string $arguments) : bool {
            return (bool) (isset($this->arguments) && \implode(\DIRECTORY_SEPARATOR, \array_intersect_assoc(\explode(\DIRECTORY_SEPARATOR, (string) $arguments), $this->arguments)) === (string) $arguments);
        }

        public function dispatch(string $path) {
            return (string) $this->getController($path);
        }           
        
        final public function getController(string $path) {
            if (\file_exists(($file = \sprintf("%s/%s.php", $this->path, $path)))) {
                \ob_start();
                require $file;
                return (string) $this->getCallbacks(\ob_get_clean());
            }            
        }            
        
        final public function getCallbacks(string $output, array $matches = []) {
            if (\is_string($output) && \preg_match_all($this->regex, $output, $matches, \PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $key => $match) {
                    if (!\is_string($data = $this->callback($match))) {
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
            $controller->path = \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path));
            if (isset($controller->path)) {
                return $controller->dispatch(\basename($path));        
            }
        }
    }    
}