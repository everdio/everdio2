<?php
namespace Components\Adapter\Dom\Xml {
    final class File extends \Components\Adapter\Dom\Xml {  
        public function __construct(string $file, string $version = NULL, string $encoding = NULL, int $options = NULL) {
            parent::__construct(file_get_contents($file), $version, $encoding, $options);
        }
    }
}