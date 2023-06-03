<?php
namespace Component\Caller\Image {
    class Create extends \Component\Caller\Image {
        public function __construct(int $width, int $height) {
            parent::__construct();
            $this->handle = \imagecreatetruecolor($width, $height);
        }
    }
}