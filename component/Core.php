<?php
namespace Component {
    abstract class Core {
        use Helpers, Dryer;
        public function __construct(private array $_parameters = []) {
           foreach ($_parameters as $parameter => $validation) {
                if ($validation instanceof \Component\Validation) {
                    $this->_parameters[$parameter] = $validation;
                }
            }            
        }

        public function __toString() : string {
            return (string) \get_class($this);
        }

        final public function __invoke(string $parameter) : Validation {
            return (object) $this->get($parameter);
        }            

        final public function __isset(string $parameter) : bool {
            return (bool) ($this->exists($parameter) && $this->_parameters[$parameter]->isValid());
        }        
        
        public function __set(string $parameter, $value) {
            if ($this->exists($parameter)) {
                return (bool) $this->_parameters[$parameter]->setValue((\is_array($value) && \is_array($this->_parameters[$parameter]->getValue()) ? \array_merge($this->_parameters[$parameter]->getValue(), $value) : $value));
            }
            
            throw new \InvalidArgumentException(sprintf("unknown parameter `%s` in %s", $parameter, \get_class($this)));
        }

        public function __get(string $parameter) {
            if ($this->exists($parameter)) {
                try {
                    return $this->_parameters[$parameter]->execute();    
                } catch (\InvalidArgumentException $exception) {   
                    throw new \InvalidArgumentException(sprintf("invalid value for parameter `%s::%s`: %s", \get_class($this), $parameter, $exception->getMessage()));
                }
            }
            
            throw new \InvalidArgumentException (sprintf("unknown parameter `%s` in %s", $parameter, \get_class($this)));
        }

        final public function __unset(string $parameter) {
            if ($this->exists($parameter)) {
                return (bool) $this->_parameters[$parameter]->setValue(false);
            }
            
            throw new \InvalidArgumentException(sprintf("unknown parameter `%s` in %s", $parameter, \get_class($this)));
        }      

        final public function exists(string $parameter) : bool {
            return (bool) \array_key_exists($parameter, $this->_parameters);
        }
        
        final public function add(string $parameter, Validation $validation, $reset = false) {
            if (!$this->exists($parameter) || $reset) {
                return (bool) $this->_parameters[$parameter] = $validation;
            }
        }
        
        final public function get(string $parameter) : Validation {
            if ($this->exists($parameter)) {
                return (object) $this->_parameters[$parameter];
            }
            
            throw new \InvalidArgumentException(sprintf("unknown parameter `%s` in %s", $parameter, \get_class($this)));
        }
        
        final public function remove($parameter) : void {
            if ($this->exists($parameter)) {
                unset ($this->_parameters[$parameter]);
            }
        }
   
        final public function parameters(array $parameters = []) : array {
            return (array) \array_intersect_key($this->_parameters, \array_flip($this->inter($parameters)));
        }
                
        final public function inter(array $parameters) : array {
            return (array) \array_diff(\array_keys($this->_parameters), $this->diff($parameters));
        }
        
        final public function diff(array $parameters = []) : array {
            return (array) \array_diff(\array_keys($this->_parameters), $parameters);
        }
        
        public function store(array $values) : void {
            foreach ($values as $parameter => $value) {
                if ($this->exists($parameter)) {
                    $this->{$parameter} = $value;
                }
            }
        }      
        
        public function restore(array $parameters = [], array $values = []) : array {
            foreach ($this->inter($parameters) as $parameter) {
                if (isset($this->{$parameter})) {
                    $values[$parameter] = $this->{$parameter};
                }
            }
            
            return (array) $values;
        }
        
        final public function reset(array $parameters = []) : void {
            $this->store(\array_fill_keys($this->inter($parameters), false));
        }
                        
        final public function validate(array $parameters = [], array $validations = []) { 
            foreach ($this->inter($parameters) as $parameter) {
                $validations[$parameter] = (bool) isset($this->{$parameter});
            }        
            
            return (array) $validations;
        }
        
        final public function isStrict(array $parameters = []) : bool {
            return (bool) !\in_array(false, $this->validate($parameters));
        }
        
        final public function isNormal(array $parameters = []) : bool {
            return (bool) \in_array(true, $this->validate($parameters));
        }
        
        final public function isEmpty(array $parameters = []) : bool {
            return (bool) !\in_array(true, $this->restore($parameters));
        }
        
        final public function import(string $serialized) : void {
            $this->_parameters = \array_merge($this->_parameters, \unserialize($serialized));
        }

        final public function export(array $parameters = []) : string {
            return (string) \serialize($this->parameters($parameters));
        }
        
        final public function querystring(array $parameters = []) : string {
            return (string) \http_build_query($this->restore($this->inter($parameters)));
        }

        final public function unique(array $parameters = [], string $salt = NULL) : string {
            return (string) \sha1($this->querystring($this->inter($parameters)) . $salt);
        }        
        
        final public function search(string $path, string $implode = NULL, string $wrap = "%s") {    
            foreach (\explode(\DIRECTORY_SEPARATOR, $path) as $value) {
                if (isset($this->{$value})) {
                    return (string) \sprintf($wrap, ($this->{$value} instanceof self ? $this->{$value}->search(\implode(\DIRECTORY_SEPARATOR, \array_diff(\explode(\DIRECTORY_SEPARATOR, $path), [$value])), $implode) : (\is_array($this->{$value}) && \array_key_exists(($key = \implode(false, \array_diff(\explode(\DIRECTORY_SEPARATOR, $path), [$value]))), $this->{$value}) ? $this->{$value}[$key] : (\is_array($this->{$value}) ? \implode($implode, $this->{$value}) : $this->{$value}))));
                }
            }        
        }
        
        private function methodClass(string $querystring) : string {
            return (string) \parse_url($querystring, \PHP_URL_HOST);            
        }        
        
        private function methodName(string $querystring) : string {
            return (string) \parse_url($querystring, \PHP_URL_SCHEME);
        }
        
        private function methodArguments(string $querystring, array $arguments = []) : array {
            \parse_str(\parse_url(\html_entity_decode($querystring), \PHP_URL_QUERY), $arguments);
            return (array) $arguments;
        }                 
        
        final protected function callAble(string $querystring) : bool {
            if (($class = $this->methodClass($querystring)) && ($name = $this->methodName($querystring))) {
                if (\method_exists($class, $name)) {
                    $reflection = new \ReflectionMethod($class, $name);
                    return (bool) ($reflection->getNumberOfRequiredParameters() <= sizeof(array_values(($arguments = $this->methodArguments($querystring)))));
                }                
            }
            
            return (bool) false;            
        }
        
        final protected function call(string $querystring) {
            if (\is_subclass_of($this, $this->methodClass($querystring))) {
                return \call_user_func_array([$this, $this->methodName($querystring)], \array_values($this->methodArguments($querystring)));
            }
        }                             
        
        final public function replace(string $content, array $parameters = [], int $instances = 2, string $replace = "{{%s}}") : string {
            foreach ($this->restore($parameters) as $parameter => $value) {      
                $content = \implode($value, \explode(\sprintf($replace, $parameter), $content, $instances));
            }
            
            return (string) $content;
        }      
        
        public function __dry() : string {
            return (string) $this->dehydrate($this->_parameters);
        }
        
        public function __destruct() {
            $this->_parameters = [];
        }        
    }
}