<?php

namespace Component\Caller\Image {

    class File extends \Component\Caller\Image {
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
                    case "image/avif":
                        $this->handle = \imagecreatefromavif($file);
                        break;
                    case "application/octet-stream":
                        $this->handle = \imagecreatefromstring(\file_get_contents($file));
                        break;
                    default:
                        throw new \InvalidArgumentException(\sprintf("IMAGE_TYPE_UNSUPPORTED %s", \image_type_to_mime_type(\exif_imagetype($file))));
                }
            } else {
                throw new \InvalidArgumentException(\sprintf("IMAGE_FILE_UNKNOWN %s", $file));
            }            
        }
    }

}