<?php
namespace Component\Validator\IsString\IsPath {
    class IsReal extends \Component\Validator\IsString\IsPath {
        const TYPE = "IS_REAL_PATH";
        const MESSAGE = "PATH_DOES_NOT_EXIST";
        public function execute($value) : bool {
            return (bool) parent::execute($value) && \realpath($value);
        }
    }
}

