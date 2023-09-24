<?php

namespace Modules\OpenWeather\Api {

    use \Component\Validator,
        \Component\Validation;

    class Model extends \Modules\Node\Xml\Model {

        public function __construct() {
            parent::__construct([
                "lang" => new Validation(false, [new Validator\IsString]),
                "lat" => new Validation(false, [new Validator\IsFloat, new Validator\IsNumeric]),
                "lon" => new Validation(false, [new Validator\IsFloat, new Validator\IsNumeric]),
                "api" => new Validation(false, [new Validator\IsString])
            ]);

            $this->use = "\Modules\OpenWeather\Api";
        }

        public function __destruct() {
            $this->remove("document");
            parent::__destruct();
        }
    }

}
