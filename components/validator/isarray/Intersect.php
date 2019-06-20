<?php
namespace Components\Validator\IsArray {
    class Intersect extends \Components\Validator\IsArray {
        const MESSAGE = "ARRAYS_NOT_MATCHING";
        
        protected $array = [];
        
        public function __construct(array $array = []) {
            $this->array = $array;
        }
        
        public function getArray() : array {
            return (array) $this->array;
        }
        
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && (array_intersect($value, $this->array) === $value));
        }
        
        public function __dry() : string {
            return (string) sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->array));
        }           
    }
}

