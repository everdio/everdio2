<?php
namespace Modules {
    trait Adapter {
        use \Components\Helpers;
        use \Components\Dryer;
        abstract public function execute(string $query);
    }
}

