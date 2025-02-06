<?php

namespace Component\Core\Adapter {

    use \Component\Validation,
        \Component\Validator;

    abstract class Wrapper extends \Component\Core\Adapter {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "adapter" => new Validation(false, [new Validator\IsArray])
                    ] + $_parameters);
        }
        
        final public function __dry(): string {
            return (string) \sprintf("new \%s(%s)", (string) $this, parent::__dry());
        }             
    }

}