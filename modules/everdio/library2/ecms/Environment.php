<?php
namespace Modules\Everdio\Library2\ECms {
    class Environment extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["environment_id" => "EnvironmentId", "environment" => "Environment", "scheme" => "Scheme", "host" => "Host", "root_path" => "RootPath", "main_path" => "MainPath", "www_path" => "WwwPath", "upload_path" => "UploadPath", "cache_path" => "CachePath", "arguments" => "Arguments", "page_not_found" => "PageNotFound", "page_event" => "PageEvent", "extension" => "Extension", "status" => "Status", "created" => "Created", "updated" => "Updated"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("environment", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["EnvironmentId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(false, [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("EnvironmentId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Environment", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("Scheme", new \Components\Validation(NULL, [new \Components\Validator\IsString\InArray(["//", "http://", "https://"])], \Components\Validation::STRICT));
$this->add("Host", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("RootPath", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(65)], \Components\Validation::STRICT));
$this->add("MainPath", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(65)], \Components\Validation::STRICT));
$this->add("WwwPath", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(65)], \Components\Validation::STRICT));
$this->add("UploadPath", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(65)], \Components\Validation::STRICT));
$this->add("CachePath", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(65)], \Components\Validation::STRICT));
$this->add("Arguments", new \Components\Validation("index.html", [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("PageNotFound", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(65)], \Components\Validation::STRICT));
$this->add("PageEvent", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(65)], \Components\Validation::STRICT));
$this->add("Extension", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(5), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("Status", new \Components\Validation("inactive", [new \Components\Validator\IsString\InArray(["active", "inactive"])], \Components\Validation::STRICT));
$this->add("Created", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
$this->add("Updated", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}