<?php
namespace Component\Caller\File\Fopen {
    class Memory extends \Component\Caller\File\Fopen {
        public function __construct() {
            parent::__construct("php://memory", "w+");
        }
    }
}