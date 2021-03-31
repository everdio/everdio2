<?php
namespace Components\Validator {
    class NotEmpty extends \Components\Validator {
        const TYPE = "NOT_EMPTY";
        const MESSAGE = "IS_EMPTY";
        public function execute($value) : bool {
            return (bool) !empty($value);
        }
    }
}
