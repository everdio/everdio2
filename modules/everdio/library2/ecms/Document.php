<?php
namespace Modules\Everdio\Library2\ECms {
    class Document extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["document_id" => "DocumentId", "account_id" => "AccountId", "environment_id" => "EnvironmentId", "parent_id" => "ParentId", "title" => "Title", "document" => "Document", "document_slug" => "DocumentSlug", "canonical" => "Canonical", "robots" => "Robots", "keywords" => "Keywords", "description" => "Description", "querystring" => "Querystring", "content" => "Content", "order" => "Order", "created" => "Created", "updated" => "Updated"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("document", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["DocumentId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(["AccountId" => "Modules\Everdio\Library2\ECms\Account", "EnvironmentId" => "Modules\Everdio\Library2\ECms\Environment"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("DocumentId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("AccountId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("EnvironmentId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("ParentId", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("Title", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(255)], \Components\Validation::STRICT));
$this->add("Document", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(255)], \Components\Validation::STRICT));
$this->add("DocumentSlug", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(255), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("Canonical", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(255), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("Robots", new \Components\Validation("index,follow", [new \Components\Validator\IsArray\Intersect(["index", "noindex", "follow", "nofollow", "noimageindex", "noarchive", "nocache", "nosnippet", "notranslate"])], \Components\Validation::STRICT));
$this->add("Keywords", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(255), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("Description", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(255), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("Querystring", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(16777215), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("Content", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(4294967295), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("Order", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(3)], \Components\Validation::STRICT));
$this->add("Created", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
$this->add("Updated", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}