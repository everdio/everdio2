<?php
namespace Modules\Image {
    class Webp extends \Modules\Image {
        const IMAGE_TYPE = 18;
        
        public function create($path) {
            imagecreatefromwebp($path);
        }

        public function export(\Modules\Image $image, \SplFileObject $file, $compression = 75) : bool {
            return (bool) imagewebp($image->image, $file->getRealPath(), $compression);
        }     

        public function display($compression = 75) {
            header('Content-Type: image/webp');
            die(imagewebp($this->image, false, $compression));
        }
    }
}