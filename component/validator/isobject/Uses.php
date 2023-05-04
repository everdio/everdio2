<?php
namespace Component\Validator\IsObject {
    class Uses extends \Component\Validator\IsObject {
        const MESSAGE = "INVALID_OBJECT_USES";
        
        public function __construct(private string $_trait) {
            
        }
        
        public function execute($value) : bool {
            return (bool) parent::execute($value) && \in_array($this->_trait, \class_uses($value));
        }
        
        public function __dry() : string {
            return (string) \sprintf("new \%s(\"%s\")", (string) $this, $this->_trait);
        }
    }
}