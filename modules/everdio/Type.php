<?php
namespace Modules\Everdio {
    class Type extends \Modules\Everdio\Library\ECms\Type {
        public function save() {
            $this->TypeSlug = $this->slug($this->Type);
            parent::save();
        }
    }
}