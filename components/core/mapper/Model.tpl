<?php
namespace @namespace@ {
    class @class@ extends @extend@ implements \Components\Core\Mapper\Base {
        public function __construct(array $values = []) {
            @model@
            $this->store($values);
            parent::__construct();            
        }

        static public function construct(array $values = []) : \Components\Core\Mapper {
            return (object) new @class@($values);
        }
    }
}