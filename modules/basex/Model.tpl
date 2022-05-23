<?php
namespace {{namespace}} {
    class {{class}} extends \Component\Core\Adapter {
        use {{use}};        
        static public $_queries = [];
        public function __construct() {
            parent::__construct({{mapper}});
        }       

        static public function construct() : self {
            return (object) new {{class}}();
        }        
    }
}