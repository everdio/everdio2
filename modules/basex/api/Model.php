<?php

namespace Modules\BaseX\Api {

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
        
        public function __destruct() {
            $this->remove("content");
            
            parent::__destruct();
        }        
    }

}
