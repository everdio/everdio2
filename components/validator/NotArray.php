<?php
namespace Components\Validator {
    class NotArray extends \Components\Validator {
        const TYPE = "NOT_ARRAY";
        const MESSAGE = "IS_ARRAY";
        public function execute($value) : bool {
            return (bool) !is_array($value);
        }
    }
}
