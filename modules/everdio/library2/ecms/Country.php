<?php
namespace Modules\Everdio\Library2\ECms {
    class Country extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["country_id" => "CountryId", "abbreviation" => "Abbreviation", "country" => "Country", "created" => "Created", "updated" => "Updated"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("country", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["CountryId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(false, [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("CountryId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Abbreviation", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(2)], \Components\Validation::STRICT));
$this->add("Country", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("Created", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
$this->add("Updated", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}