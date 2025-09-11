<?php

namespace Modules\Node\Adapter\Url {

    use Component\Validation,
        Component\Validator;

    final class Xml extends \Modules\Node\Adapter\Url {

        use \Modules\Node\Xml\Url;

        final public function __construct(array $_parameters = []) {
            parent::__construct([
                "model" => new Validation("\Modules\Node\Xml\Url\Model", [new Validator\IsString])
                    ] + $_parameters);
        }
    }

}
