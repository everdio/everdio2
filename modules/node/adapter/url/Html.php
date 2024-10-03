<?php

namespace Modules\Node\Adapter\Url {

    use Component\Validation,
        Component\Validator;

    final class Html extends \Modules\Node\Adapter\Url {

        use \Modules\Node\Html\Url;

        final public function __construct(array $_parameters = []) {
            parent::__construct([
                "model" => new Validation("\Modules\Node\Html\Url\Model", [new Validator\IsString])
                    ] + $_parameters);
        }
    }

}
