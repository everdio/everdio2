<?php
namespace Components {
    trait Adapter {
        use Helpers;
        use Dryer;
        abstract public function execute(string $query);
    }
}

