<?php
namespace Modules\Everdio\Library2\ECms {
    class Currency extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["currency_id" => "CurrencyId", "currency" => "Currency", "abbreviation" => "Abbreviation", "symbol" => "Symbol", "format" => "Format", "created" => "Created", "updated" => "Updated"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("currency", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["CurrencyId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(false, [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("CurrencyId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Currency", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("Abbreviation", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(3)], \Components\Validation::STRICT));
$this->add("Symbol", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(10)], \Components\Validation::STRICT));
$this->add("Format", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("Created", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
$this->add("Updated", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}