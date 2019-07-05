<?php
namespace Components\Adapter\Dom {
    trait File {
        private $file = false;
        public function __construct(string $file, string $version = NULL, string $encoding = NULL, int $options = NULL) {
            parent::__construct(file_get_contents($file), $version, $encoding, $options);
            $this->file = $file;
        }
    }
}