<?php

namespace Modules\Node\Model {

    use Component\Validation,
        Component\Validator;

    abstract class Content extends \Modules\Node\Model {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "content" => new Validation(false, [new Validator\IsString\IsFile, new Validator\IsString\IsUrl, new Validator\IsString]),
                    ] + $_parameters);
        }
    }

}
