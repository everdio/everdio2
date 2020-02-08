<?php
namespace Components {
    class Core {
        use Helpers;
        private $_parameters = [];        
        
        public function __toString() : string {
            return (string) get_class($this);
        }

        public function __invoke(string $parameter) : Validation {
            return (object) $this->get($parameter);
        }            

        public function __isset(string $parameter) : bool {
            return (bool) ($this->exists($parameter) && $this->_parameters[$parameter]->isValid());
        }        
        
        public function __set(string $parameter, $value) : bool {
            if ($this->exists($parameter)) {
                return (bool) $this->_parameters[$parameter]->set((is_array($value) && is_array($this->_parameters[$parameter]->get()) ? array_merge($this->_parameters[$parameter]->get(), $value) : $value));
            }
            
            throw new Event(sprintf("unknown parameter `%s` in %s", $parameter, get_class($this)));
        }

        public function __get(string $parameter) {
            if ($this->exists($parameter)) {
                try {
                    return $this->_parameters[$parameter]->execute();    
                } catch (\Components\Event $event) {
                    throw new Event(sprintf("invalid parameter `%s`; %s", $parameter, $event->getMessage()));
                }
            }
            
            throw new Event(sprintf("unknown parameter `%s` in %s", $parameter, get_class($this)));
        }

        public function __unset(string $parameter) : bool {
            if ($this->exists($parameter)) {
                return (bool) $this->_parameters[$parameter]->set(false);
            }
            throw new Event(sprintf("unknown parameter `%s` in %s", $parameter, get_class($this)));
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
            
            throw new Event(sprintf("unknown parameter `%s` in %s", $parameter, get_class($this)));
        }
        
        final public function remove($parameter) {
            if ($this->exists($parameter)) {
                unset ($this->_parameters[$parameter]);
            }
        }
        
        final public function parameters() : array {
            return (array) array_keys($this->_parameters);
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
        
        final public function restore(array $parameters = [], array $values = []) : array {
            foreach ($this->inter($parameters) as $parameter) {
                if (isset($this->{$parameter})) {
                    $values[$parameter] = $this->{$parameter};
                }
            }
            
            return (array) $values;
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

        final public function feed(string $querystring, array $values = []) {
            parse_str($querystring, $values);
            if (array_key_exists((string) $this, $values)) {
                $this->store($values[(string) $this]);
            }
        }
        
        final public function import(string $querystring, array $values = []) {
            parse_str($querystring, $values);
            $this->store($values);
        }

        final public function querystring(array $parameters = []) : string {
            return (string) http_build_query($this->restore($parameters), true);
        }
        
        final public function reset(array $parameters = []) {
            $this->store(array_fill_keys($this->inter($parameters), false));
        }
        
        final public function match(array $values) {
            return (bool) ($values === $this->restore(array_keys($values)));
        }
        
        public function search(string $path) {            
            foreach (explode(DIRECTORY_SEPARATOR, $path) as $parameter) {   
                if (array_key_exists($parameter, $this->_parameters)) {
                    return (isset($this->{$parameter}) && $this->{$parameter} instanceof $this ? $this->{$parameter}->search(implode(DIRECTORY_SEPARATOR, array_diff(explode(DIRECTORY_SEPARATOR, $path), [$parameter]))) : $this->{$parameter});
                }
            }
        }
    }
}