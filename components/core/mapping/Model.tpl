<?php
namespace @namespace@ {
    class @class@ extends @extend@ implements \Components\Core\Mapping\Base {
        public function __construct(array $values = []) {
            @model@
            $this->store($values);
            parent::__construct();            
        }

        static public function construct(array $values = []) : \Components\Core\Mapping {
            return (object) new @class@($values);
        }
    }
}