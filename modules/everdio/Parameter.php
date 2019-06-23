<?php
namespace Modules\Everdio {
    class Parameter extends \Modules\Everdio\Library\EDistribution\Parameter {
        public function save() {
            $this->ParameterSlug = $this->slug($this->Parameter);
            parent::save();
        }
    }
}