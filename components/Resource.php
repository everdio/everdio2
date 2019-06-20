<?php
namespace Components {
    trait Resource {
        use Helpers;
        use Dryer;
        abstract public function execute(string $query);
    }
}

