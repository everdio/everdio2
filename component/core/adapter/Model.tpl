<?php
namespace {{namespace}} {
    class {{class}} extends \Component\Core\Adapter {
        use {{use}};      
        public function __construct(array $parameters = []) {
            parent::__construct({{mapper}} + $parameters);
        }
        
        static public function construct() : self {
            return (object) new {{class}}();
        }                
    }
}