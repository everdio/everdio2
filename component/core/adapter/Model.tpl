<?php
namespace {{namespace}} {
    class {{class}} extends \Component\Core\Adapter {
        use {{use}};      
        public function __construct(array $_parameters = []) {
            parent::__construct({{mapper}} + $_parameters);
        }
        
        static public function construct(array $_parameters = []) : self {
            return (object) new {{class}}($_parameters);
        }                
    }
}