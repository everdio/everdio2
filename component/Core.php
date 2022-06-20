<?php
namespace Component {
    abstract class Core {
        use Helpers, Dryer;
        public function __construct(private array $_parameters = []) {
            foreach ($_parameters as $parameter => $validation) {
                $this->add($parameter, $validation);
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
            
            throw new \InvalidArgumentException(\sprintf("unknown parameter `%s` in %s", $parameter, \get_class($this)));
        }

        public function __get(string $parameter) {
            if ($this->exists($parameter)) {
                try {
                    return $this->_parameters[$parameter]->execute();    
                } catch (\RuntimeException $exception) {   
                    throw new \InvalidArgumentException(\sprintf("invalid value for parameter `%s::%s`: %s", \get_class($this), $parameter, $exception->getMessage()));
                }
            }
            
            throw new \InvalidArgumentException (\sprintf("unknown parameter `%s` in %s", $parameter, \get_class($this)));
        }

        final public function __unset(string $parameter) {
            if ($this->exists($parameter)) {
                return (bool) $this->_parameters[$parameter]->setValue(false);
            }
            
            throw new \InvalidArgumentException(\sprintf("unknown parameter `%s` in %s", $parameter, \get_class($this)));
        }      

        final public function exists(string $parameter) : bool {
            return (bool) \array_key_exists($parameter, $this->_parameters);
        }
        
        final public function add(string $parameter, Validation $validation, bool $reset = false) {
            if (!$this->exists($parameter) || $reset) {
                return (bool) $this->_parameters[$parameter] = $validation;
            }
        }
        
        final public function get(string $parameter) : Validation {
            if ($this->exists($parameter)) {
                return (object) $this->_parameters[$parameter];
            }
            
            throw new \InvalidArgumentException(\sprintf("unknown parameter `%s` in %s", $parameter, \get_class($this)));
        }
        
        final public function remove(string $parameter) : void {
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
        
        public function store(array $values) : self {
            foreach ($values as $parameter => $value) {
                if ($this->exists($parameter)) {
                    $this->{$parameter} = $value;
                }
            }
            
            return (object) $this;
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
                        
        private function validate(array $parameters = [], array $validations = []) { 
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
        
        final public function duplicate(self $core, array $parameters = []) : self {
            $core->import($this->export($parameters));                        
            return (object) $core;
        }
        
        final public function querystring(array $parameters = []) : string {
            return (string) \http_build_query($this->restore($this->inter($parameters)));
        }

        final public function unique(array $parameters = [], string $salt = NULL) : string {
            return (string) \sha1($this->querystring($this->inter($parameters)) . $salt);
        }        
        
        final public function merge(self $core, array $mapping = []) {
            foreach ($core->restore($this->inter(\array_keys($mapping))) as $parameter => $value) {
                if ($this->exists($mapping[$parameter])) {
                    $this->{$mapping[$parameter]} = $value;
                }
            }
        }

        final public function search(string $path) {
            foreach (\explode(\DIRECTORY_SEPARATOR, $path) as $parameter) {
                if (isset($this->{$parameter})) {
                    if ($this->{$parameter} instanceof self) {
                        return $this->{$parameter}->search(\implode(\DIRECTORY_SEPARATOR, \array_diff(\explode(\DIRECTORY_SEPARATOR, $path), [$parameter])));
                    } else{
                        if (\is_array($this->{$parameter})) {
                            return $this->haystack(\implode(\DIRECTORY_SEPARATOR, \array_diff(\explode(\DIRECTORY_SEPARATOR, $path), [$parameter])), $this->{$parameter});
                        }
                        return $this->{$parameter};        
                    }
                }
                return (string) false;
            }
        }             
        
        final public function haystack(string $path, array $haystack, array $parts = []) {
            foreach (($parts = \explode(\DIRECTORY_SEPARATOR, $path)) as $needle) {    
                if (\array_key_exists($needle, $haystack)) {
                    if (\sizeof($parts) !== 1) {
                        if ($haystack[$needle] instanceof self) {
                            return $haystack[$needle]->search(\implode(\DIRECTORY_SEPARATOR, \array_diff(\explode(\DIRECTORY_SEPARATOR, $path), [$needle])));
                        } else{
                            if (\is_array($haystack[$needle])) {
                                return $this->haystack(\implode(\DIRECTORY_SEPARATOR, \array_diff(\explode(\DIRECTORY_SEPARATOR, $path), [$needle])), $haystack[$needle]);
                            }
                            return $haystack[$needle];        
                        }
                    }
                    return $haystack[$needle];
                } 
                return (string) false;
            }            
        }

        final public function callback(string $url, array $arguments = []) {
            if (($scheme = \parse_url($url, \PHP_URL_SCHEME)) && \method_exists($this, $scheme)) {
                if (($query = \parse_url(\html_entity_decode($url), \PHP_URL_QUERY))) {
                    \parse_str($query, $arguments);
                }
                           
                return \call_user_func_array([$this, $scheme], \array_values($arguments));
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