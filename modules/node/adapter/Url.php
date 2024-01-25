<?php

namespace Modules\Node\Adapter {

    use Component\Validation,
        Component\Validator;

    abstract class Url extends \Modules\Node\Adapter {
        
        public function __construct() {
            parent::__construct([
                "url" => new Validation(false, [new Validator\IsString\IsUrl])]);
            
            $this->adapter = ["url"];
        }
    }

}
