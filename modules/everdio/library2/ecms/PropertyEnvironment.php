<?php
namespace Modules\Everdio\Library2\ECms {
    class PropertyEnvironment extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["property_id" => "PropertyId", "environment_id" => "EnvironmentId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("property_environment", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["PropertyId", "EnvironmentId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(["EnvironmentId" => "Modules\Everdio\Library2\ECms\Environment", "PropertyId" => "Modules\Everdio\Library2\ECms\Property"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("PropertyId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("EnvironmentId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}