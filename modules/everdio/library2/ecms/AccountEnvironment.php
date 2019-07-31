<?php
namespace Modules\Everdio\Library2\ECms {
    class AccountEnvironment extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["account_id" => "AccountId", "environment_id" => "EnvironmentId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("account_environment", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["AccountId", "EnvironmentId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(["AccountId" => "Modules\Everdio\Library2\ECms\Account", "EnvironmentId" => "Modules\Everdio\Library2\ECms\Environment"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("AccountId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("EnvironmentId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}