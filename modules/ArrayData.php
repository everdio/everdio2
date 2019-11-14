<?php
namespace Modules {
    class ArrayData extends \Components\Core\Adapter {
        public function __construct(array $array) {
            parent::__construct("array");
            $this->instance = $array;
        }
    }
}

