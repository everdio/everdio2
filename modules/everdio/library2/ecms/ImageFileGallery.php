<?php
namespace Modules\Everdio\Library2\ECms {
    class ImageFileGallery extends Modules\Everdio\Library2\ECms {
        public function __construct(array $values = []) {
            $this->add("mapping", new \Components\Validation(["image_file_id" => "ImageFileId", "gallery_id" => "GalleryId", "order" => "Order"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("table", new \Components\Validation("image_file_gallery", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
$this->add("keys", new \Components\Validation(["ImageFileId", "GalleryId"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("relations", new \Components\Validation(["GalleryId" => "Modules\Everdio\Library2\ECms\Gallery", "ImageFileId" => "Modules\Everdio\Library2\ECms\ImageFile"], [new \Components\Validator\IsArray], \Components\Validation::NORMAL));
$this->add("ImageFileId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("GalleryId", new \Components\Validation(NULL, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(11)], \Components\Validation::STRICT));
$this->add("Order", new \Components\Validation(1, [new \Components\Validator\IsInteger, new \Components\Validator\Len\Smaller(3)], \Components\Validation::STRICT));
            $this->store($values);
        }
    }
}