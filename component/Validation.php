<?php
namespace Component {   
    class Validation {
        use Dryer, Helpers;
        
        const NORMAL = "NORMAL";
        const STRICT = "STRICT";             
        
        protected $value = false;
        private $messages = [], $validated = [], $validators = [];   
        
        public $types = [], $validate = self::NORMAL;                
        
        public function __construct($value = false, array $validators, string $validate = self::NORMAL) {
            $this->value = $value;
            foreach ($validators as $validator) {
                $this->validators[(string) $validator] = $validator;
                $this->types[(string) $validator] = $validator::TYPE;
                $this->messages[(string) $validator] = $validator::MESSAGE;
            }
            
            $this->validate = \strtoupper($validate);
        }
        
        public function __toString() : string {
            return (string) \get_class($this);
        }
        
        public function __isset($type) : bool {
            return (bool) \in_array($type, $this->types);
        }
        
        public function __get(string $validator) : \Component\Validator {
            if (\array_key_exists($validator, $this->validators)) {
                return (object) $this->validators[$validator];
            }
            
            throw new \LogicException(sprintf("unknown validator %s", $validator));
        }
        
        public function has(array $types) : bool { 
            return (bool) \sizeof(\array_intersect($this->types, $types));
        }
        
        public function match(array $types) : bool {
            return (bool) (\sizeof(\array_intersect($this->types, $types)) === sizeof($this->types));
        }

        public function hasTypes(array $types, int $sizeof = 1) : bool {
            return (bool) (\sizeof(\array_intersect($types, $this->types)) >= $sizeof);
        }
        
        public function setValue($value) : bool { 
            return (bool) ($this->value = $this->hydrate($value));
        }
        
        public function getValue() { 
            return $this->value;
        }
        
        public function validated(bool $validation = true) : array {
            return (array) \array_intersect_key($this->validators, \array_flip(\array_keys($this->validated, $validation)));
        }

        public function isValid() : bool {
            foreach ($this->validators as $validator) {
                $this->validated[(string) $validator] = $validator->execute($this->value);
            }
            
            return (bool) (\sizeof($this->validated) && (($this->validate === self::NORMAL && \in_array(true, $this->validated)) || ($this->validate === self::STRICT && !\in_array(false, $this->validated))));
        }        

        public function execute() {
            if ($this->isValid()) {
                return $this->value;
            }
            
            throw new \InvalidArgumentException(sprintf("`%s` validation for value %s (%s)", $this->validate, $this->substring($this->dehydrate($this->value), 0, 150), \implode("+", \array_intersect_key($this->messages, \array_flip(\array_keys($this->validated, false))))));
        }

        public function __dry() : string {
            $validators = [];
            
            foreach ($this->validators as $validator) {
                $validators[] = $validator->__dry();
            }
                        
            return (string) \sprintf("new \%s(%s, [%s], \Component\Validation::%s)", (string) $this, $this->dehydrate($this->value), \implode(", ", $validators), \strtoupper($this->validate));
        }        
    }
}