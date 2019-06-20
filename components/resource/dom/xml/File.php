<?php
namespace Components\Resource\Dom\Xml {
    class File extends \Components\Resource\Dom\Xml {  
        public function __construct(string $file, string $version = NULL, string $encoding = NULL, int $options = NULL) {
            parent::__construct(file_get_contents($file), $version, $encoding, $options);
        }
    }
}