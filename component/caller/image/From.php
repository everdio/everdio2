<?php
namespace Component\Caller\Image {
    class From extends \Component\Caller\Image {
        public function __construct(string $file) {
            parent::__construct();
            
            switch (\image_type_to_mime_type(\exif_imagetype($file))) {
                case "image/jpeg":
                    $this->handle = \imagecreatefromjpeg($file);
                    break;
                case "image/png":
                    $this->handle = \imagecreatefrompng($file);
                    break;
                case "image/gif":
                    $this->handle = \imagecreatefromgif($file);
                    break;
                case "image/webp":
                    $this->handle = \imagecreatefromwebp($file);
                    break;                
            }     
        }
    }
}