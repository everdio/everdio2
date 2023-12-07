<?php

namespace Component\Core\Adapter {

    use \Component\Validation,
        \Component\Validator;

    abstract class Wrapper extends \Component\Core\Adapter implements \Component\Core\Adapter\Wrapper\Base {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "id" => new Validation(false, [new Validator\IsString, new Validator\IsInteger]),
                "adapter" => new Validation(["id"], [new Validator\IsArray])
                    ] + $_parameters);
        }

        final public function __dry(): string {
            
            return (string) \sprintf("(new \%s)->store(%s)", (string) $this, $this->dehydrate($this->restore($this->diff())));
        }
    }

}