<?php
namespace Modules\Everdio\Library2\ECms {
    class ImageFile extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["image_file_id" => "ImageFileId", "image_id" => "ImageId", "file_id" => "FileId", "environment_id" => "EnvironmentId", "language_id" => "LanguageId", "rotate" => "Rotate", "top" => "Top", "left" => "Left", "content" => "Content", "source" => "Source", "created" => "Created", "updated" => "Updated"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("image_file", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["ImageFileId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(["EnvironmentId" => "Modules\Everdio\Library2\ECms\Environment", "FileId" => "Modules\Everdio\Library2\ECms\File", "ImageId" => "Modules\Everdio\Library2\ECms\Image", "LanguageId" => "Modules\Everdio\Library2\ECms\Language"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("ImageFileId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("ImageId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("FileId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("EnvironmentId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("LanguageId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Rotate", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(3)], \Components\Validation::STRICT));
$this->add("Top", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(4)], \Components\Validation::STRICT));
$this->add("Left", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(4)], \Components\Validation::STRICT));
$this->add("Content", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(255)], \Components\Validation::STRICT));
$this->add("Source", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(255), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("Created", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
$this->add("Updated", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}