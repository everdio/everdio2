<?php
namespace @Namespace@ {
    class @Class@ extends \Components\Core\Mapper {
        use @Use@;        
        public function __construct(array $values = []) {
            parent::__construct(@Mapper@);
            $this->store($values);
        }
                
        static public function construct(array $values = []) : self {
            return (object) new @Class@($values);
        }        
    }
}