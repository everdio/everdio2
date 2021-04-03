<?php
namespace @namespace@ {
    class @class@ extends \Component\Core\Parameters {
        use @use@;      
        public function __construct(array $parameters = []) {
            parent::__construct(@mapper@ + $parameters);
        }
    }
}