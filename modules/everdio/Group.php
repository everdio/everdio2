<?php
namespace Modules\Everdio {
    class Group extends \Modules\Everdio\Library\ECms\Group {
        public function save() {
            $this->GroupSlug = $this->slug($this->Group);
            parent::save();
        }
    }
}