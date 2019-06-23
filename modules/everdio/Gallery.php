<?php
namespace Modules\Everdio {
    class Gallery extends \Modules\Everdio\Library\ECms\Gallery {
        public function save() {
            $this->GallerySlug = $this->slug($this->Gallery);
            parent::save();
        }
    }
}