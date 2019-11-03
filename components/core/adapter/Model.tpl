<?php
namespace @namespace@ {
    class @class@ extends @extends@ {
        public function __construct(array $values = [], $key = "@key@") {
            parent::__construct($key);
            @model@
            $this->store($values);
        }
        
        static public function construct(array $values = []) : self {
            return (object) new @class@($values);
        }        
    }
}