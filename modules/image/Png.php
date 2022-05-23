<?php
namespace Modules\Image {
    class Png extends \Modules\Image {
        const TYPE = 3;        
        const MIME = 'image/png';
        
        protected function create($file) {
            return \imagecreatefrompng($file);
        }

        public function export($image, $file, $compression = 75) : bool {
            return (bool) \imagepng($image->image, $file, $compression);
        }        

        public function display($compression = 75) {
            \header(\sprintf("Content-Type: %s", self::MIME));
            die(\imagepng($this->image, false, $compression));
        }
    }
}