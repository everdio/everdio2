<?php
namespace @Namespace@ {
    class @Class@ extends @Extends@ {
        public function __construct(array $values = [], $key = "@Key@") {
            parent::__construct($key);
            @Model@
            $this->store($values);
        }
        
        static public function construct(array $values = []) : self {
            return (object) new @Class@($values);
        }        
    }
}