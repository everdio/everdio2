<?php
namespace Component\Caller\Image {
    class From extends \Component\Caller\Image {
        public function __construct(string $file) {
            parent::__construct();
            if (\is_file($file)) {
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
                    default:
                        throw new \InvalidArgumentException(\sprintf("%s incorrect %s", $file, \image_type_to_mime_type(\exif_imagetype($file))));
                }     
            } else {
                throw new \InvalidArgumentException(\sprintf("%s does not exist", $file));
            }
        }
    }
}