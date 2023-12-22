<?php

namespace Modules\Node\Adapter {

    use Component\Validation,
        Component\Validator;

    abstract class Document extends \Modules\Node\Adapter {
        
        public function __construct() {
            parent::__construct([
                "document" => new Validation(false, [new Validator\IsString\IsFile, new Validator\IsString\IsUrl, new Validator\IsString])]);
            
            $this->adapter = ["document"];
        }
    }

}
