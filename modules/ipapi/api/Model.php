<?php

namespace Modules\IpApi\Api {

    use \Component\Validator,
        \Component\Validation;

    class Model extends \Modules\Node\Model {

        use \Modules\IpApi;
        
        public function __construct() {
            parent::__construct([
                "api" => new Validation(false, [new Validator\IsString]),
                "ip" => new Validation(false, [new Validator\IsString])
            ]);

            $this->use = "\Modules\IpApi\Api";
        }

        public function __destruct() {
            $this->remove("document");
            parent::__destruct();
        }
    }

}
