<?php
namespace Components\Validator\IsString {
    class InArray extends \Components\Validator\IsString {
        const MESSAGE = "NOT_IN_ARRAY";
        const TYPE = "STRING_IN_ARRAY";
        protected $array = [];        
        
        public function __construct(array $array) {
            $this->array = $array;
        }
        
        public function getArray() : array {
            return (array) $this->array;
        }
        
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && (in_array($value, $this->array) || array_key_exists($value, $this->array)));
        }
        
        public function __dry() : string {
            return (string) sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->array));
        }
    }
}