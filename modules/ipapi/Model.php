<?php

namespace Modules\IpApi {

    use \Component\Validator,
        \Component\Validation;

    class Model extends \Modules\Node\Model\Content {

        use \Modules\Node\Xml\Content;
        
        public function __construct() {
            parent::__construct([
                "api" => new Validation(false, [new Validator\IsString]),
                "ip" => new Validation(false, [new Validator\IsString])
            ]);

            $this->use = "\Modules\IpApi\Api";
        }
        
        public function __destruct() {
            $this->remove("content");
            
            parent::__destruct();
        }
    }

}
