<?php

namespace Modules\Table {

    use Component\Validation,
        Component\Validator;

    abstract class Adapter extends \Component\Core\Adapter\Models {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "dsn" => new Validation(false, [new Validator\IsString]),
                    ] + $_parameters);
            
            $this->adapter = ["dsn"];
        }
    }

}
