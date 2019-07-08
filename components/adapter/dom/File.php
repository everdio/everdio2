<?php
namespace Components\Adapter\Dom {
    trait File {
        private $file = false;
        public function __construct(string $file, string $version = NULL, string $encoding = NULL, int $options = LIBXML_HTML_NOIMPLIED | LIBXML_NOCDATA | LIBXML_NOERROR | LIBXML_NONET | LIBXML_NOWARNING) {
            parent::__construct(file_get_contents($file), $version, $encoding, $options);
            $this->file = $file;
        }
    }
}