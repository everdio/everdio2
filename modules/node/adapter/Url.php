<?php

namespace Modules\Node\Adapter {

    use Component\Validation,
        Component\Validator;

    abstract class Url extends \Modules\Node\Adapter {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "url" => new Validation(false, [new Validator\IsString\IsUrl])
                    ] + $_parameters);

            $this->adapter = ["url"];
        }
    }

}
