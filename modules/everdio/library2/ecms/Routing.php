<?php
namespace Modules\Everdio\Library2\ECms {
    class Routing extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["locale_id" => "LocaleId", "document_id" => "DocumentId", "environment_id" => "EnvironmentId", "execute_id" => "ExecuteId", "routing" => "Routing", "display" => "Display", "status" => "Status", "created" => "Created", "updated" => "Updated"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("routing", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["EnvironmentId", "DocumentId", "LocaleId", "ExecuteId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(["DocumentId" => "Modules\Everdio\Library2\ECms\Document", "EnvironmentId" => "Modules\Everdio\Library2\ECms\Environment", "ExecuteId" => "Modules\Everdio\Library2\ECms\Execute", "LocaleId" => "Modules\Everdio\Library2\ECms\Locale"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("LocaleId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("DocumentId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("EnvironmentId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("ExecuteId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Routing", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(255)], \Components\Validation::STRICT));
$this->add("Display", new \Components\Validation(NULL, [new \Components\Validator\IsArray\Intersect(["sitemap", "navigation"]), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("Status", new \Components\Validation("inactive", [new \Components\Validator\IsString\InArray(["active", "inactive"])], \Components\Validation::STRICT));
$this->add("Created", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
$this->add("Updated", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}