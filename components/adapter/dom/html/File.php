<?php
namespace Components\Adapter\Dom\Html {
    final class File extends \Components\Adapter\Dom\Html {  
        public function __construct(string $file, string $version = NULL, string $encoding = NULL, int $options = NULL) {
            parent::__construct(file_get_contents($file), $version, $encoding, $options);
        }        
    }
}