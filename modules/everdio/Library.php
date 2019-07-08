<?php
namespace Modules\Everdio {    
    abstract class Library extends \Components\Core\Library {
        public function __construct($adapter) {
            parent::__construct($adapter);
            $this->namespace = "Modules\Everdio\Library";
        }
    }
}