<?php
namespace Component\Validator\IsString {
    class Contains extends \Component\Validator\IsString {
        const MESSAGE = "STRING_DOES_NOT_CONTAIN";
        
        public function __construct(private array $_array = false) {
            
        }
        
        public function execute($value) : bool {
            if (parent::execute($value)) {
                foreach ($this->_array as $string) {
                    if (\strpos($value, (string) $string) !== false) {
                        return (bool) true;
                    }
                }
            }
            
            return (bool) false;
        }
        
        public function __dry() : string {
            return (string) \sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->_array));
        }           
    }
}