<?php
namespace Components\Resource\Dom\Html {
    class File extends \Components\Resource\Dom\Html {  
        public function __construct(string $file, string $version = NULL, string $encoding = NULL, int $options = NULL) {
            parent::__construct(file_get_contents($file), $version, $encoding, $options);
        }        
    }
}