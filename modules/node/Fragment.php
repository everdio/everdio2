<?php
namespace Modules\Node {
    final class Fragment extends \Component\Validation {
        public function __construct(string $xpath, array $validations = [], string $wrap = "(%s)") {

            
            parent::__construct(\sprintf($wrap, \implode(\DIRECTORY_SEPARATOR, $xparts)), [new \Component\Validator\IsString\IsXPath]);
        }
    }
}

            
            