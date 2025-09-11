<?php

namespace Modules\Node\Model {

    use Component\Validation,
        Component\Validator;

    abstract class Content extends \Modules\Node\Model {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "id" => new Validation(false, [new Validator\IsString, new Validator\IsNumeric]),
                "content" => new Validation(false, [new Validator\IsString]),
                    ] + $_parameters);
            $this->adapter = ["id"];
        }
    }

}
