<?php
namespace Component\Validator\IsObject {
    class Of extends \Component\Validator\IsObject {
        const MESSAGE = "INVALID_OBJECT_OF";
        
        protected $instance = false;
        
        public function __construct($instance) {
            $this->instance = $instance;
        }
        
        public function execute($value) : bool {
            return (bool) parent::execute($value) && $value instanceof $this->instance;
        }
        
        public function __dry() : string {
            return (string) sprintf("new \%s(\"%s\")", (string) $this, $this->instance);
        }
    }
}