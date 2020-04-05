<?php
namespace Components {   
    class Validation {
        use Dryer;
        use Helpers;
        
        const NORMAL = "normal";
        const STRICT = "strict";        

        public $types = [];        
        
        protected $value = false;        
        
        private $validated = [];                
        
        private $messages = [];
        
        private $validate = false;        
        
        private $validators = [];
        
        public function __construct($value, array $validators, $validate = self::NORMAL) {
            $this->set($value);
            
            foreach ($validators as $validator) {
                if ($validator instanceof Validator) {
                    $this->validators[(string) $validator] = $validator;
                    $this->types[(string) $validator] = $validator::TYPE;
                    $this->messages[(string) $validator] = $validator::MESSAGE;
                }
            }
            
            $this->validate = strtolower($validate);
        }

        public function __toString() : string {
            return (string) get_class($this);
        }
        
        public function __isset(string $validator) : bool {
            return (booL) array_key_exists($validator, $this->validators);
        }
        
        public function __invoke(string $validator) : \Components\Validator {
            if (isset($this->{$validator})) {
                return (object) $this->validators[$validator];
            }

            throw new Event("unknown validator");
        }
        
        public function set($value) : bool { 
            return (bool) ($this->value = $this->hydrate($value));
        }
        
        public function get() { 
            return $this->value;
        }
        
        public function validated() : array {
            return (array) array_intersect_key($this->validators, array_flip(array_keys($this->validated, true)));
        }
        
        public function hasType(string $type) : bool {
            return (bool) in_array($type, $this->types);
        }
        
        public function isValid() : bool {
            foreach ($this->validators as $validator) {
                $this->validated[(string) $validator] = $validator->execute($this->value);
            }
            
            return (bool) (sizeof($this->validated) && (($this->validate === self::NORMAL && in_array(true, $this->validated)) || ($this->validate === self::STRICT && !in_array(false, $this->validated))));
        }        

        public function execute() {
            if ($this->isValid()) {
                return $this->value;
            }
            
            throw new Event(sprintf("`%s` validation for value %s (%s)", $this->validate, $this->substring($this->dehydrate($this->value), 0, 150), implode("+", array_intersect_key($this->messages, array_flip(array_keys($this->validated, false))))));
        }

        public function __dry() : string {
            $validators = [];
            
            foreach ($this->validators as $validator) {
                $validators[] = $validator->__dry();
            }
                        
            return (string) sprintf("new \%s(%s, [%s], \Components\Validation::%s)", (string) $this, $this->dehydrate($this->value), implode(", ", $validators), strtoupper($this->validate));
        }        
    }
}