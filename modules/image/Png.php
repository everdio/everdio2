<?php
namespace Modules\Image {
    class Png extends \Modules\Image {
        const IMAGE_TYPE = 3;        
        
        public function create($path) {
            return imagecreatefrompng($path);
        }

        public function export(\Modules\Image $image, \SplFileObject $file, $compression = 75) : bool {
            return (bool) imagepng($image->image, $file->getRealPath(), $compression);
        }        

        public function display($compression = 75) {
            header('Content-Type: image/png');        
            die(imagepng($this->image, false, $compression));
        }
    }
}