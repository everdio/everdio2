<?php

namespace Component\Core\Adapter\Wrapper\Controller\Model\Http {

    use \Component\Validation,
        \Component\Validator;

    class Authorization extends \Component\Core\Adapter\Wrapper\Controller\Model\Http {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "authorization" => new Validation(false, [new Validator\IsArray\Intersect\Key(["HTTP_AUTHORIZATION"])], Validation::NORMAL),
                "key" => new Validation(false, [new Validator\IsString])
                    ] + $_parameters);
        }

        public function setup(): void {
            parent::setup();
            
            if (isset($this->authorization)) {
                $this->key = $this->authorization["HTTP_AUTHORIZATION"];
            }
        }
    }

}