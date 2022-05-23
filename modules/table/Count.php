<?php 
namespace Modules\Table {
    final class Count extends \Component\Validation {
        public function __construct() {
            parent::__construct("SELECT count(*) ", [new \Component\Validator\IsString]);
        }
    }
}