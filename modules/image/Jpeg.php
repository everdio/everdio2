<?php
namespace Modules\Image {
    class Jpeg extends \Modules\Image {
        const TYPE = 2;
        const MIME = "image/jpeg";
        
        protected function create($file) {
            return \imagecreatefromjpeg($file);
        }

        public function export($image, $file, $compression = 75) : bool {
            return (bool) \imagejpeg($image->image, $file, $compression);
        }

        public function display($compression = 75) {
            \header(\sprintf("Content-Type: %s", self::MIME));
            echo \imagejpeg($this->image, false, $compression);
        }
    }
}