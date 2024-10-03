<?php

namespace Modules\Table {

    use Component\Validation;
    use Component\Validator;

    abstract class Model extends \Component\Core\Adapter\Mapper\Model {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "dsn" => new Validation(false, array(new Validator\IsString)),
                "resource" => new Validation(false, array(new Validator\IsString)),
                "keys" => new Validation(false, array(new Validator\IsArray))
                    ] + $_parameters);
        }
    }

}