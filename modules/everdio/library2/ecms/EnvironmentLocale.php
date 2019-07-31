<?php
namespace Modules\Everdio\Library2\ECms {
    class EnvironmentLocale extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["environment_id" => "EnvironmentId", "locale_id" => "LocaleId", "host" => "Host", "web_path" => "WebPath"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("environment_locale", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["EnvironmentId", "LocaleId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(["EnvironmentId" => "Modules\Everdio\Library2\ECms\Environment", "LocaleId" => "Modules\Everdio\Library2\ECms\Locale"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("EnvironmentId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("LocaleId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Host", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(65), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("WebPath", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
            $this->store($values);
        }
    }
}