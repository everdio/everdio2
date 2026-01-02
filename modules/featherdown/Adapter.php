<?php

namespace Modules\Featherdown {

    use \Component\Validation,
        \Component\Validator;

    class Adapter extends \Component\Core\Adapter\Model {

        use \Modules\BaseX;

        public function __construct() {
            parent::__construct([
                "url" => new Validation(false, [new Validator\IsString\IsUrl]),
                "username" => new Validation(false, [new Validator\IsString]),
                "password" => new Validation(false, [new Validator\IsString]),
                "locale" => new Validation(false, [new Validator\IsString, new Validator\Len\Equal(5)], Validation::STRICT),
                "location" => new Validation(false, [new Validator\IsString]),
                "from" => new Validation(false, [new Validator\IsString\IsDatetime\IsDate]),
                "until" => new Validation(false, [new Validator\IsString\IsDatetime\IsDate]),
                "customer" => new Validation(false, [new Validator\IsArray]),
                "accommodations" => new Validation(false, [new Validator\IsArray]),
                "products" => new Validation(false, [new Validator\IsArray])]);

            $this->use = "\Modules\Featherdown";
            $this->adapter = ["url", "username"];
        }

        public function setup(): void {
            
        }

        public function deploy(): void {
            unset($this->locale);
            unset($this->location);
            unset($this->from);
            unset($this->until);
            unset($this->customer);
            unset($this->accommodations);
            unset($this->products);

            parent::deploy();
        }
    }

}
