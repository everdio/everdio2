<?php

namespace Modules\OpenWeather {

    use \Component\Validator,
        \Component\Validation;

    class Model extends \Modules\Node\Model\Content {

        use \Modules\Node\Xml\Content;

        public function __construct() {
            parent::__construct([
                "lang" => new Validation(false, [new Validator\IsString]),
                "lat" => new Validation(false, [new Validator\IsFloat]),
                "lon" => new Validation(false, [new Validator\IsFloat]),
                "api" => new Validation(false, [new Validator\IsString]),
            ]);
            $this->use = "\Modules\OpenWeather\Api";
        }

        public function deploy(): void {
            $this->remove("content");
            parent::deploy();
        }
    }

}
