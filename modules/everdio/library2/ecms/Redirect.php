<?php
namespace Modules\Everdio\Library2\ECms {
    class Redirect extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["redirect_id" => "RedirectId", "redirect" => "Redirect", "routing" => "Routing", "status" => "Status", "hits" => "Hits", "created" => "Created", "updated" => "Updated"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("redirect", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["RedirectId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(false, [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("RedirectId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Redirect", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(255)], \Components\Validation::STRICT));
$this->add("Routing", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(255)], \Components\Validation::STRICT));
$this->add("Status", new \Components\Validation(302, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(3)], \Components\Validation::STRICT));
$this->add("Hits", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(6)], \Components\Validation::STRICT));
$this->add("Created", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
$this->add("Updated", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}