<?php
namespace Modules {
    trait Instance {
        use \Components\Helpers;
        use \Components\Dryer;
        abstract public function execute(string $query);
    }
}

