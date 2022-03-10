<?php
namespace Component\Validator {
    class IsResource extends \Component\Validator {
        const TYPE = "IS_RESOURCE";
        const MESSAGE = "INVALID_RESOURCE";
        public function execute($value) : bool {
            return (bool) \is_resource($value);
        }
    }
}
