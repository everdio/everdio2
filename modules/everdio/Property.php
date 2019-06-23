<?php
namespace Modules\Everdio {
    class Property extends \Modules\Everdio\Library\ECms\Property {
        public function save() {
            $this->PropertySlug = $this->slug($this->Property);
            parent::save();
        }
    }
}