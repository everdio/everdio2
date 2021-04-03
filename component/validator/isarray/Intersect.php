<?php
namespace Component\Validator\IsArray {
    class Intersect extends \Component\Validator\IsArray {
        const MESSAGE = "ARRAYS_NOT_MATCHING";
        const TYPE = "IS_ARRAY_INTERSECT";
        protected $array = [];
        
        public function __construct(array $array = []) {
            $this->array = $array;
        }
        
        public function getArray() : array {
            return (array) $this->array;
        }
        
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && (sizeof($this->array) && array_intersect($value, $this->array) === $value));
        }
        
        public function __dry() : string {
            return (string) sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->array));
        }           
    }
}

