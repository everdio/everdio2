<?php
namespace {{namespace}} {

    class {{class}} extends \Modules\Memcached\BaseX {
    
        public function __construct(array $_parameters = []) {
            parent::__construct({{parameters}} + $_parameters);
        }
    }
    
}