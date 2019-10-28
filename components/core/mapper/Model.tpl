<?php
namespace @namespace@ {
    class @class@ extends @extends@ {
        public function __construct(array $values = []) {
            @model@
            $this->store($values);
        }
        
        static public function construct(array $values = []) : \Components\Core\Mapper {
            return (object) new @class@($values);
        }        
    }
}