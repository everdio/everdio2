<?php
namespace Modules\Everdio {
    class Image extends \Modules\Everdio\Library\ECms\Image {
        public function save() {
            $this->ImageSlug = $this->slug($this->Image);
            parent::save();
        }
    }
}