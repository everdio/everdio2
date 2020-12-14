<?php
namespace @namespace@ {
    class @class@ extends \Components\Core\Adapter {
        use @use@;        
        public function __construct(array $parameters = []) {
            parent::__construct(@mapper@ + $parameters);
        }
    }
}