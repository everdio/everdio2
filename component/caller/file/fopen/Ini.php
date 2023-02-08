<?php
namespace Component\Caller\File\Fopen {
    class Ini extends \Component\Caller\File\Fopen {
        public function __construct(string $path) {
            parent::__construct(sprintf("%s.ini", $path), "c");
        }
    }
}