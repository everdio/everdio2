<?php
namespace @namespace@ {
    class @class@ extends \Components\Core\Mapper implements \Components\Core\Mapper\Base {
        use @use@;        
        public function __construct(array $values = []) {
            parent::__construct(@mapper@);
            $this->store($values);
        }
                
        static public function construct(array $values = []) : self {
            return (object) new @class@($values);
        }        
    }
}