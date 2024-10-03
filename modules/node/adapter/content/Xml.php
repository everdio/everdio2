<?php

namespace Modules\Node\Adapter\Content {

    use Component\Validation,
        Component\Validator;

    final class Xml extends \Modules\Node\Adapter\Content {

        use \Modules\Node\Xml\Content;

        final public function __construct(array $_parameters = []) {
            parent::__construct([
                "model" => new Validation("\Modules\Node\Xml\Content\Model", [new Validator\IsString])
                    ] + $_parameters);
        }
    }

}
