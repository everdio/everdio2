<?php
namespace Components\Parser {
    final class Ini extends \Components\Parser {
        const EXTENSION = ".ini";  
        
        final static public function parse(string $content) : array {
            return (array) parse_ini_string($content, true);
        }
    }
}
