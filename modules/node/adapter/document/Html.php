<?php

namespace Modules\Node\Adapter\Document {

    use Component\Validation,
        Component\Validator;

    final class Html extends \Modules\Node\Adapter\Document {

        use \Modules\Node\Html\Document;

        final public function __construct(array $_parameters = []) {
            parent::__construct([
                "model" => new Validation("\Modules\Node\Html\Document\Model", [new Validator\IsString])
                    ] + $_parameters);
        }
    }

}
