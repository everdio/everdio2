<?php
namespace Components {   
    class Validation {
        use Dryer, Helpers;
        
        const NORMAL = "normal";
        const STRICT = "strict";        

        private $types = [];        
        
        protected $value = false;        
        
        private $validated = [];                
        
        private $messages = [];
        
        private $validate = false;        
        
        private $validators = [];
        
        public function __construct($value, array $validators, $validate = self::NORMAL) {
            $this->setValue($value);
            foreach ($validators as $validator) {
                $this->validators[(string) $validator] = $validator;
                $this->types[(string) $validator] = $validator::TYPE;
                $this->messages[(string) $validator] = $validator::MESSAGE;
            }
            
            $this->validate = strtolower($validate);
        }
        
        public function __toString() : string {
            return (string) get_class($this);
        }
        
        public function hasType(string $type) : bool {
            return (bool) in_array($type, $this->types);
        }
        
        public function hasValidator(string $validator) : bool {
            return (bool) array_key_exists($validator, $this->validators);
        }

        public function setValue($value) : bool { 
            return (bool) ($this->value = $this->hydrate($value));
        }
        
        public function getValue() { 
            return $this->value;
        }
        
        public function validated(bool $validation = true) : array {
            return (array) array_intersect_key($this->validators, array_flip(array_keys($this->validated, $validation)));
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
            
            throw new \InvalidArgumentException(sprintf("`%s` validation for value %s (%s)", $this->validate, $this->substring($this->dehydrate($this->value), 0, 150), implode("+", array_intersect_key($this->messages, array_flip(array_keys($this->validated, false))))));
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