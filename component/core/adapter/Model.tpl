<?php

namespace {{namespace}} {
    
    class {{class}} extends \Component\Core\Adapter {
        
        use \Component\Core\Threading, {{use}};
        
        public function __construct(array $_parameters = []) {
            parent::__construct(
                {{parameters}} + $_parameters);
        }
    }
    
}