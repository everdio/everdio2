<?php
namespace Component\Caller {
    class Image extends \Component\Caller {
        public function __construct() {
            parent::__construct("image%s");
        }
        
        public function create(int $width, int $height) {
            $this->handle = \imagecreate($width, $height);
        }
        
        public function createTruecolor(int $width, int $height) {
            $this->handle = \imagecreatetruecolor($width, $height);
        }
        
        public function createGif(string $file) {
            $this->handle = \imagecreatefromwebp($file);
        }
        
        public function createJpeg(string $file) {
            $this->handle = \imagecreatefromjpeg($file);
        }
        
        public function createPng(string $file) {
            $this->handle = \imagecreatefrompng($file);
        }
        
        public function crop(int $width, int $height) {   
            $this->handle = parent::crop(["y" => (($this->sy() - $height) / 2), "x" => (($this->sx() - $width) / 2), "width" => $width, "height" => $height]);
        }            
        
        public function scale(int $width = NULL, int $height = NULL) {
            $this->handle = parent::scale((int) (!$width && $height ? $this->sx() * ($height / $this->sy()) : $width), (int) ($width && !$height ? $this->sy() * ($width / $this->sx()) : $height));
        }
        
        public function __destruct() {
            $this->destroy();
        }
    }
}