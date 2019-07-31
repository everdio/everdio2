<?php
namespace Modules\Everdio\Library2\ECms {
    class Image extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["image_id" => "ImageId", "image" => "Image", "image_slug" => "ImageSlug", "image_path" => "ImagePath", "prefix" => "Prefix", "width" => "Width", "height" => "Height", "resample" => "Resample", "scale" => "Scale", "compression" => "Compression", "input" => "Input", "output" => "Output", "created" => "Created", "updated" => "Updated"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("image", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["ImageId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(false, [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("ImageId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Image", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("ImageSlug", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45)], \Components\Validation::STRICT));
$this->add("ImagePath", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(65)], \Components\Validation::STRICT));
$this->add("Prefix", new \Components\Validation(NULL, [new \Components\Validator\IsString, new \Components\Validator\Len\Smaller(45), new \Components\Validator\IsNull], \Components\Validation::NORMAL));
$this->add("Width", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(4)], \Components\Validation::STRICT));
$this->add("Height", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(4)], \Components\Validation::STRICT));
$this->add("Resample", new \Components\Validation(NULL, [new \Components\Validator\IsArray\Intersect(["width", "height"])], \Components\Validation::STRICT));
$this->add("Scale", new \Components\Validation(0, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(2)], \Components\Validation::STRICT));
$this->add("Compression", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(3)], \Components\Validation::STRICT));
$this->add("Input", new \Components\Validation(NULL, [new \Components\Validator\IsArray\Intersect(["jpg", "jpeg", "png", "gif", "webp"])], \Components\Validation::STRICT));
$this->add("Output", new \Components\Validation(NULL, [new \Components\Validator\IsString\InArray(["jpg", "png", "gif", "webp"])], \Components\Validation::STRICT));
$this->add("Created", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
$this->add("Updated", new \Components\Validation(false, [new \Components\Validator\IsDatetime\Timestamp("Y-m-d H:i:s")], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}