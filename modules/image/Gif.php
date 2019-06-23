<?php
namespace Modules\Image {
    class Gif extends \Modules\Image {
        const IMAGE_TYPE = 1;        
        
        public function create($path) {
            return imagecreatefromgif($path);
        }    

        public function export(\Modules\Image $image, \SplFileObject $file, $compression = 75) : bool {        
            return (bool) imagegif($image->image, $file->getRealPath(), $compression);
        }

        public function display($compression = 75) {
            header('Content-Type: image/gif');
            die(imagegif($this->image, false, $compression));
        }
    }
}