<?php

namespace Modules\Node\Adapter\Document {

    use Component\Validation,
        Component\Validator;

    final class Xml extends \Modules\Node\Adapter\Document {

        use \Modules\Node\Xml\Document;

        final public function __construct(array $_parameters = []) {
            parent::__construct([
                "model" => new Validation("\Modules\Node\Xml\Document\Model", [new Validator\IsString])
                    ] + $_parameters);
        }
    }

}
