<?php

namespace {{namespace}} {
    
    class {{class}} extends \Component\Core\Adapter\Mapper implements \Component\Core\Adapter\Mapper\Base {
        
        use \Component\Core\Threading, {{use}};
        
        public function __construct(array $values = []) {
            parent::__construct({{parameters}});
            $this->store($values);
        }
    }
    
}