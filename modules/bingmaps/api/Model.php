<?php
namespace Modules\Bingmaps\Api {
    use \Component\Validator, \Component\Validation;    
    class Model extends \Modules\Node\Xml\Model {
        public function __construct() {
            parent::__construct([
                "point" => new Validation(false, [new Validator\IsString]),
            ]);
        }
        
        public function __destruct() {
            $this->remove("document");
            parent::__destruct();
        }
    }
}
