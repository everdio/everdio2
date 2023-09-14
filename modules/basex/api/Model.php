<?php

namespace Modules\BaseX\Api {

    use \Component\Validation,
        \Component\Validator;

    class Model extends \Modules\Node\Xml\Model {

        public function __construct() {
            parent::__construct([
                "api" => new Validation(false, [new Validator\IsString])
            ]);

            $this->use = "\Modules\BaseX\Api";
        }

        public function __destruct() {
            $this->remove("document");
            parent::__destruct();
        }
    }

}
