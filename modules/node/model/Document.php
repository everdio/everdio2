<?php

namespace Modules\Node\Model {

    use Component\Validation,
        Component\Validator;

    abstract class Document extends \Modules\Node\Model {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "document" => new Validation(false, [new Validator\IsString\IsFile, new Validator\IsString\IsUrl, new Validator\IsString]),
                    ] + $_parameters);
        }
    }

}
