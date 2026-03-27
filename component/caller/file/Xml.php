<?php

namespace Component\Caller\File {

    class Xml extends \Component\Caller\File {

        public function __construct(string $file, string $encoding = "UTF-8") {
            parent::__construct("xml_%s", $file);
            $this->handle = \xml_parser_create($encoding);
        }
    }

}