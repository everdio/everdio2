<?php
namespace Components\Core\Adapter {
    class Instance extends \Components\Core\Adapter {
        public function __construct(string $key, $instance) {
            self::$instances[$key] = $instance;
            parent::__construct($key);
        }
    }
}
