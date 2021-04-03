<?php
namespace Component {
    abstract class Core {
        use Helpers, Dryer;
        public $_parameters = [];
        public function __construct(array $parameters = []) {
            foreach ($parameters as $parameter => $validation) {
                if ($validation instanceof \Component\Validation) {
                    $this->_parameters[$parameter] = $validation;
                }
            }
        }
        
        public function __toString() : string {
            return (string) get_class($this);
        }

        public function __invoke(string $parameter) : Validation {
            return (object) $this->get($parameter);
        }            

        public function __isset(string $parameter) : bool {
            return (bool) ($this->exists($parameter) && $this->_parameters[$parameter]->isValid());
        }        
        
        public function __set(string $parameter, $value) {
            if ($this->exists($parameter)) {
                return (bool) $this->_parameters[$parameter]->setValue((is_array($value) && is_array($this->_parameters[$parameter]->getValue()) ? array_merge($this->_parameters[$parameter]->getValue(), $value) : $value));
            }
            
            throw new \InvalidArgumentException(sprintf("unknown or missing parameter `%s` in %s", $parameter, get_class($this)));
        }

        public function __get(string $parameter) {
            if ($this->exists($parameter)) {
                try {
                    return $this->_parameters[$parameter]->execute();    
                } catch (\InvalidArgumentException $exception) {      
                    throw new \InvalidArgumentException (sprintf("invalid value for parameter `%s` in %s ", $parameter, get_class($this)), 2, $exception);
                }
            }
            
            throw new \InvalidArgumentException (sprintf("unknown or missing parameter `%s` in %s", $parameter, get_class($this)));
        }

        public function __unset(string $parameter) {
            if ($this->exists($parameter)) {
                return (bool) $this->_parameters[$parameter]->setValue(false);
            }
            throw new \InvalidArgumentException(sprintf("unknown or missing parameter `%s` in %s", $parameter, get_class($this)));
        }      

        final public function exists(string $parameter) : bool {
            return (bool) array_key_exists($parameter, $this->_parameters);
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
            
            throw new \InvalidArgumentException(sprintf("unknown or missing parameter `%s` in %s", $parameter, get_class($this)));
        }
        
        final public function remove($parameter) {
            if ($this->exists($parameter)) {
                unset ($this->_parameters[$parameter]);
            }
        }
   
        final public function parameters(array $parameters = []) : array {
            return (array) array_intersect_key($this->_parameters, array_flip($this->inter($parameters)));
        }
                
        final public function inter(array $parameters) : array {
            return (array) array_diff(array_keys($this->_parameters), $this->diff($parameters));
        }
        
        final public function diff(array $parameters = []) : array {
            return (array) array_diff(array_keys($this->_parameters), $parameters);
        }
        
        public function store(array $values) {
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
        
        final public function reset(array $parameters = []) {
            $this->store(array_fill_keys($this->inter($parameters), false));
        }
                        
        final public function validate(array $parameters = [], array $validations = []) { 
            foreach ($this->inter($parameters) as $parameter) {
                $validations[$parameter] = (bool) isset($this->{$parameter});
            }        
            
            return (array) $validations;
        }
        
        final public function isStrict(array $parameters = []) : bool {
            return (bool) !in_array(false, $this->validate($parameters));
        }
        
        final public function isNormal(array $parameters = []) : bool {
            return (bool) in_array(true, $this->validate($parameters));
        }
        
        final public function isEmpty(array $parameters = []) : bool {
            return (bool) !in_array(true, $this->restore($parameters));
        }
        
        public function import(string $querystring, array $cores = []) {
            parse_str($querystring, $cores);
            foreach ($cores as $core => $values) {
                if ((string) $this === $core && is_array($values)) {
                    $this->store($values);
                }
            }
        }

        public function export(array $parameters = []) : string {
            return (string) http_build_query([(string) $this => $this->restore($this->inter($parameters))]);
        }
        
        final public function querystring(array $parameters = []) : string {
            return (string) http_build_query($this->restore($this->inter($parameters)));
        }

        final public function unique(array $parameters = [], string $salt = NULL) : string {
            return (string) sha1($this->querystring($this->inter($parameters)) . $salt);
        }
        
        final public function __clone() {
            $this->_parameters = $this->_parameters;
        }
        
        public function __dry() : string {
            return (string) $this->dehydrate($this->_parameters);
        }
    }
}