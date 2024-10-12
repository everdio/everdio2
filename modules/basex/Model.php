<?php

namespace Modules\BaseX {

    use \Component\Validation,
        \Component\Validator;

    final class Model extends \Modules\Node\Model\Content {

        use \Modules\Node\Xml\Content;

        public function __construct() {
            parent::__construct([
                "api" => new Validation(false, [new Validator\IsString])
            ]);

            $this->use = "\Modules\BaseX\Api";
        }

        public function create(): void {
            $this->remove("content");

            parent::create();
        }
    }

}
