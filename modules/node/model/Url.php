<?php

namespace Modules\Node\Model {

    use Component\Validation,
        Component\Validator;

    abstract class Url extends \Modules\Node\Model {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "url" => new Validation(false, [new Validator\IsString\IsUrl]),
                    ] + $_parameters);
        }
    }

}
