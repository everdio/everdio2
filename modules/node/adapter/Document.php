<?php

namespace Modules\Node\Adapter {

    use Component\Validation,
        Component\Validator;

    abstract class Document extends \Modules\Node\Adapter {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "document" => new Validation(false, [new Validator\IsString\IsFile, new Validator\IsString\IsUrl, new Validator\IsString])
                    ] + $_parameters);

            $this->adapter = ["document"];
        }
    }

}
