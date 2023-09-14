<?php

namespace Component\Caller\File\Fopen {

    class Cache extends \Component\Caller\File\Fopen {

        public function __construct(string $path) {
            parent::__construct(sprintf("%s.cache", $path), "c+");
        }

        final public function write($content): int {
            return (int) parent::write(\serialize($content));
        }

        final public function read() {
            return \unserialize(parent::read());
        }
    }

}