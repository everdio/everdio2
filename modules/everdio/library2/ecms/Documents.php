<?php
namespace Modules\Everdio\Library2\ECms {
    class Documents extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["account_id" => "AccountId", "account" => "Account", "first_name" => "FirstName", "last_name" => "LastName", "locale_id" => "LocaleId", "locale" => "Locale", "date_short" => "DateShort", "date_long" => "DateLong", "language_id" => "LanguageId", "language" => "Language", "execute_id" => "ExecuteId", "route" => "Route", "methods" => "Methods", "cache_ttl" => "CacheTtl", "environment_id" => "EnvironmentId", "Routing" => "Routing", "display" => "Display", "document_id" => "DocumentId", "parent_id" => "ParentId", "title" => "Title", "document" => "Document", "document_slug" => "DocumentSlug", "canonical" => "Canonical", "robots" => "Robots", "keywords" => "Keywords", "description" => "Description", "querystring" => "Querystring", "content" => "Content", "order" => "Order", "created" => "Created", "updated" => "Updated"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("documents", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(false, [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(false, [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("AccountId", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Account", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("FirstName", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("LastName", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("LocaleId", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Locale", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("DateShort", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("DateLong", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("LanguageId", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Language", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("ExecuteId", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Route", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("Methods", new \Components\Validation("get", [new \Components\Validator\IsArray\Intersect(["get", "post", "put", "delete", "head"])], \Components\Validation::STRICT));
$this->add("CacheTtl", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(4)], \Components\Validation::STRICT));
$this->add("EnvironmentId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Routing", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(309), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("Display", new \Components\Validation(NULL, [new \Components\Validator\IsArray\Intersect(["sitemap", "navigation"]), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("DocumentId", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
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
$this->add("Created", new \Components\Validation("0000-00-00 00:00:00", [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
$this->add("Updated", new \Components\Validation("0000-00-00 00:00:00", [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}