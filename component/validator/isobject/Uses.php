<?php
namespace Component\Validator\IsObject {
    class Uses extends \Component\Validator\IsObject {
        const MESSAGE = "INVALID_OBJECT_USES";
        
        protected $trait = false;
        
        public function __construct($instance) {
            $this->trait = $instance;
        }
        
        public function execute($value) : bool {
            return (bool) parent::execute($value) && \in_array($this->trait, \class_uses($value));
        }
        
        public function __dry() : string {
            return (string) \sprintf("new \%s(\"%s\")", (string) $this, $this->trait);
        }
    }
}