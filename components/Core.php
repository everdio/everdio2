<?php
namespace Components {
    class Core {
        use Dryer;
        use Helpers;
        
        private $parameters = [];

        public function __toString() : string {
            return (string) get_class($this);
        }

        public function __invoke($parameter) : Validation {
            if ($this->exists($parameter)) {
                return (object) $this->parameters[$parameter];
            }
            
            throw new Event(sprintf("unknown parameter `%s` in %s", $parameter, get_class($this)));
        }            

        public function __isset(string $parameter) : bool {
            return (bool) ($this->exists($parameter) && $this->parameters[$parameter]->validate());
        }        
        
        public function __set(string $parameter, $value) : bool {
            if ($this->exists($parameter)) {
                return (bool) $this->parameters[$parameter]->set((is_array($value) && is_array($this->parameters[$parameter]->get()) ? array_merge($this->parameters[$parameter]->get(), $value) : $this->hydrate($value)));
            }
            
            throw new Event(sprintf("unknown parameter `%s` in %s", $parameter, get_class($this)));
        }

        public function __get(string $parameter) {
            if ($this->exists($parameter)) {
                try {
                    return $this->parameters[$parameter]->execute();    
                } catch (\Components\Event $event) {
                    throw new Event(sprintf("invalid parameter `%s`; %s", $parameter, $event->getMessage()));
                }
            }
            
            throw new Event(sprintf("unknown parameter `%s` in %s", $parameter, get_class($this)));
        }

        public function __unset(string $parameter) : bool {
            return (bool) ($this->exists($parameter) && $this->parameters[$parameter]->set(false));
        }      
        
        final public function invoke(string $parameter) : Validation {
            return (object) $this($parameter);
        }
        
        final public function exists($parameter) : bool {
            return (bool) array_key_exists($parameter, $this->parameters);
        }
        
        final public function add($parameter, Validation $validation, $reset = false) {
            if (!$this->exists($parameter) || $reset) {
                return (bool) $this->parameters[$parameter] = $validation;
            }
        }
 
        final public function remove($parameter) {
            if ($this->exists($parameter)) {
                unset ($this->parameters[$parameter]);
            }
        }
        
        final public function inter(array $parameters) : array {
            if (sizeof($parameters)) {
                return (array) array_diff(array_keys($this->parameters), $this->diff($parameters));
            }
            
            return (array) array_keys($this->parameters);
        }
        
        final public function diff(array $parameters = []) : array {
            return (array) array_diff(array_keys($this->parameters), $parameters);
        }

        final public function store(array $values) {
            foreach ($values as $parameter => $value) {
                if ($this->exists($parameter)) {
                    $this->{$parameter} = (!is_array($value) && in_array(\Components\Validator\IsArray::TYPE, $this($parameter)->types) ? explode(",", $value) : $value);
                }
            }
        }
        
        final public function restore(array $parameters = [], array $return = []) : array {
            foreach ($this->inter($parameters) as $parameter) {
                if (isset($this->{$parameter})) {
                    $return[$parameter] = $this->{$parameter};
                }
            }
            
            return (array) $return;
        }
        
        final public function validate(array $parameters = [], array $validations = []) { 
            foreach ($this->inter($parameters) as $parameter) {
                $validations[$parameter] = (bool) isset($this->{$parameter});
            }        
            
            return (array) $validations;
        }

        final public function feed($querystring, array $values = []) {
            parse_str($querystring, $values);
            if (array_key_exists((string) $this, $values)) {
                $this->store($values[(string) $this]);
            }
        }

        final public function query(array $parameters = []) : string {
            return (string) http_build_query(array((string) $this => $this->restore($parameters)), true);
        }        
        
        final public function reset(array $parameters = []) {
            foreach ($this->inter($parameters) as $parameter) {
                unset ($this->{$parameter});
            }
        }
        
        final public function import(Core $core, array $parameters) {
            foreach ($parameters as $parameter) {
                $this->add($parameter, $core($parameter));
            }
        }
        
        public function __dry() : string {
            $output = [];     
            
            foreach ($this->parameters as $parameter => $validation) {
                $output[] = sprintf("\$this->add(\"%s\", %s);", $parameter, $validation->__dry());
            }
            
            return (string) implode(PHP_EOL, $output);
        }   
    }
}