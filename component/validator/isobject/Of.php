<?php
namespace Component\Validator\IsObject {
    class Of extends \Component\Validator\IsObject {
        const MESSAGE = "INVALID_OBJECT_OF";
        
        public function __construct(private string $_instance) {
            
        }
        
        public function execute($value) : bool {
            return (bool) parent::execute($value) && $value instanceof $this->_instance;
        }
        
        public function __dry() : string {
            return (string) \sprintf("new \%s(\"%s\")", (string) $this, $this->_instance);
        }
    }
}