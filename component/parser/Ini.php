<?php
namespace Component\Parser {
    final class Ini extends \Component\Parser {
        const EXTENSION = "ini";  
        
        final static public function parse(string $content) : array {
            return (array) parse_ini_string($content, true, INI_SCANNER_TYPED);
        }
    }
}
