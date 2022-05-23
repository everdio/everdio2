<?php
namespace Modules\Image {
    class Webp extends \Modules\Image {
        const TYPE = 18;
        const MIME = "image/webp";
        
        protected function create($file){
            return \magecreatefromwebp($file);
        }

        public function export($image, $file, $compression = 75) : bool {
            return (bool) \imagewebp($image->image, $file, $compression);
        }     

        public function display($compression = 75) {
            \header(\sprintf("Content-Type: %s", self::MIME));
            die(\imagewebp($this->image, false, $compression));
        }
    }
}