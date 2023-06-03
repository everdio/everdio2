<?php
namespace Component\Caller {
    abstract class Image extends \Component\Caller {
        public function __construct() {
            parent::__construct("image%s");
        }
  
        public function crop(int $width, int $height) {   
            $this->handle = parent::crop(["y" => (($this->sy() - $height) / 2), "x" => (($this->sx() - $width) / 2), "width" => $width, "height" => $height]);
        }            
        
        public function scale(int $width = NULL, int $height = NULL) {
            $this->handle = parent::scale((int) (!$width && $height ? $this->sx() * ($height / $this->sy()) : $width), (int) ($width && !$height ? $this->sy() * ($width / $this->sx()) : $height));
        }
    }
}