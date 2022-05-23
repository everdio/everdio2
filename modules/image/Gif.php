<?php
namespace Modules\Image {
    class Gif extends \Modules\Image {
        const TYPE = 1;        
        const MIME = "image/gif";
                
        protected function create($file) {
            return \imagecreatefromgif($file);
        }    

        public function export($image, $file, $compression = 75) : bool {        
            return (bool) \imagegif($image->image, $file, $compression);
        }

        public function display($compression = 75) {
            \header(\sprintf("Content-Type: %s", self::MIME));
            die(\imagegif($this->image, false, $compression));
        }
    }
}