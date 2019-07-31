<?php
namespace Modules\Everdio\Library2\ECms {
    class Group extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["group_id" => "GroupId", "group" => "Group", "group_slug" => "GroupSlug", "status" => "Status", "created" => "Created", "updated" => "Updated"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("group", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["GroupId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(false, [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("GroupId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Group", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("GroupSlug", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("Status", new \Components\Validation("inactive", [new \Components\Validator\IsString\InArray(["active", "inactive"])], \Components\Validation::STRICT));
$this->add("Created", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
$this->add("Updated", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}