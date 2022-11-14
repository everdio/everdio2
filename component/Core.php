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

        public function __invoke(string $parameter) : Validation {
            return (object) $this->get($parameter);
        }            

        final public function __isset(string $parameter) : bool {
            return (bool) $this->isset($parameter);
        }        

        public function __set(string $parameter, $value) {
            $this->setValue($parameter, $value);
        }

        public function __get(string $parameter) {
            return $this->getValue($parameter);
        }

        public function __unset(string $parameter) {
            $this->unset($parameter);
        }     
        
        final public function isset(string $parameter) : bool {
            return (bool) ($this->exists($parameter) && $this->_parameters[$parameter]->isValid());
        }
        
        public function unset(string $parameter) {
            if ($this->exists($parameter)) {
                return (bool) $this->_parameters[$parameter]->setValue(false);
            }
            
            throw new \InvalidArgumentException(\sprintf("unknown parameter `%s` in %s", $parameter, \get_class($this)));            
        }
        
        final public function getValue($parameter) {
            if ($this->exists($parameter)) {
                try {
                    return $this->_parameters[$parameter]->execute();
                } catch (\ValueError $ex) {    
                    throw new \UnexpectedValueException(\sprintf("invalid value for parameter `%s::%s`: %s", \get_class($this), $parameter, $ex->getMessage()));
                }
            }
            
            throw new \InvalidArgumentException(\sprintf("unknown parameter `%s` in %s", $parameter, \get_class($this)));            
        }
        
        final public function setValue($parameter, $value) {
            if ($this->exists($parameter)) {
                return (bool) $this->_parameters[$parameter]->setValue((\is_array($value) && \is_array($this->_parameters[$parameter]->getValue()) ? \array_merge($this->_parameters[$parameter]->getValue(), $value) : $value));
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
        
        public function remove(string $parameter) : void {
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
        
        final public function sizeof(array $parameters = []) : int {
            return (int) \sizeof($this->inter($parameters));
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

        public function querystring(array $parameters = []) : string {
            return (string) \http_build_query($this->restore($this->inter($parameters)));
        }        
        
        final public function reset(array $parameters = []) : void {
            $this->store(\array_fill_keys($this->inter($parameters), false));
        }
                        
        final public function validations(array $parameters = [], array $validations = []) { 
            foreach ($this->inter($parameters) as $parameter) {
                $validations[$parameter] = (bool) isset($this->{$parameter});
            }        
            
            return (array) $validations;
        }

        final public function import(string $serialized) : void {
            $this->_parameters = \array_merge($this->_parameters, \unserialize($serialized));
        }

        final public function export(array $parameters = []) : string {
            return (string) \serialize($this->parameters($parameters));
        }


        final public function unique(array $parameters = [], string $salt = NULL) : string {
            return (string) \sha1($this->querystring($this->inter($parameters)) . $salt);
        }        
        
        final public function replace(string $content, array $parameters = [], int $instances = 99, string $replace = "{{%s}}") : string {
            foreach ($this->restore($parameters) as $parameter => $value) {     
                $content = \implode($value, \explode(\sprintf($replace, $parameter), $content, $instances));
            }
            
            return (string) $content;
        }             
        
        /*
         * This function recursively strips a path based string and returns until it can no longer proceed
         * first checks whenever the path part is a parameter
         *     second then checks if the parameter it's value is another $this object if so, it will proceed in there (back to first)
         *     third if the value is not a $this it returns the value (whatever it is)
         * fourth if the part is not a paramter, it will try a callback
         * $arguments will be passed on to the callback, this is to make callbacks like 
         *     finder:?path=find/another/callback:&argument[1]=test&argument[2]=test
         */
        final public function finder(string $path, array $arguments = [], string $seperator = \DIRECTORY_SEPARATOR) {
            foreach (\explode($seperator, $path) as $part) {
                return (isset($this->{$part}) ? ($this->{$part} instanceof self ? $this->{$part}->finder(\implode($seperator, \array_diff(\explode($seperator, $path), [$part])), $arguments) : $this->{$part}) : $this->callback($part, $arguments));
            }
        }
    
        /*
         * This function uses the Uniform Resource Locator (URL) to access methods within $this
         * SCHEME = any method within $this scope
         * optional: HOST = any internal PHP function to 1) pass process a method return or 2) processing standalone
         * optional: QUERY = the arguments to feed the method with
         */
        final public function callback(string $url, array $arguments = []) {
            $function = \parse_url(\html_entity_decode($url), \PHP_URL_HOST);
            if (($query = \parse_url(\html_entity_decode($url), \PHP_URL_QUERY))) {
                \parse_str($query, $arguments);
            }            
            if (($method = \parse_url($url, \PHP_URL_SCHEME))) {
                try {
                    return ($function ? \call_user_func($function, \call_user_func_array([$this, $method], \array_values($arguments))) : \call_user_func_array([$this, $method], \array_values($arguments)));
                } catch (\TypeError $ex) {
                    throw new \BadMethodCallException($ex->getMessage());
                } catch (\ErrorException $ex) {
                    throw new \InvalidArgumentException($ex->getMessage());
                }                              
            } elseif ($function) {
                try {
                    return \call_user_func_array($function, \array_values($arguments));
                } catch (\TypeError $ex) {                            
                    throw new \BadFunctionCallException($ex->getMessage());
                }
            }               
        }                         
    
        /*
         * deprecated, use finder which is way more awesome
         */
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
        
        /*
         * deprecated, use finder which is way more awesome
         */        
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

        public function __dry() : string {
            return (string) $this->dehydrate($this->_parameters);
        }
        
        public function __destruct() {
            unset ($this->_parameters);
        }        
    }
}