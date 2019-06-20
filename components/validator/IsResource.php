<?php
namespace Components\Validator {
    class IsResource extends \Components\Validator {
        const TYPE = "IS_RESOURCE";
        const MESSAGE = "INVALID_RESOURCE";
        public function execute($value) : bool {
            return (bool) is_resource($value);
        }
    }
}
