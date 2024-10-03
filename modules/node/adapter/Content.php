<?php

namespace Modules\Node\Adapter {

    use Component\Validation,
        Component\Validator;

    abstract class Content extends \Modules\Node\Adapter {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "content" => new Validation(false, [new Validator\IsString\IsSstring])
                    ] + $_parameters);

            $this->adapter = ["content"];
        }
    }

}
