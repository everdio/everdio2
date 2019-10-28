<?php
namespace Modules\Everdio {
    class Image extends \Modules\Everdio\Library2\Image {
        public function save() {
            $this->ImageSlug = $this->slug($this->Image);
            parent::save();
        }
    }
}