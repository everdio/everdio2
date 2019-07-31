<?php
namespace Modules\Everdio\Library2\ECms {
    class PropertyLanguage extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["property_id" => "PropertyId", "language_id" => "LanguageId", "content" => "Content", "created" => "Created", "updated" => "Updated"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("property_language", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["PropertyId", "LanguageId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(["LanguageId" => "Modules\Everdio\Library2\ECms\Language", "PropertyId" => "Modules\Everdio\Library2\ECms\Property"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("PropertyId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("LanguageId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Content", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(255)], \Components\Validation::STRICT));
$this->add("Created", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
$this->add("Updated", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}