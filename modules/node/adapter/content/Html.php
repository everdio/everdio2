<?php

namespace Modules\Node\Adapter\Content {

    use Component\Validation,
        Component\Validator;

    final class Html extends \Modules\Node\Adapter\Content {

        use \Modules\Node\Html\Content;

        final public function __construct(array $_parameters = []) {
            parent::__construct([
                "model" => new Validation("\Modules\Node\Html\Content\Model", [new Validator\IsString])
                    ] + $_parameters);
        }
    }

}
