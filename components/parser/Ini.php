<?php
namespace Components\Parser {
    class Ini extends \Components\Parser {
        const EXTENSION = ".ini";  
        
        static public function parse(string $content) : array {
            return (array) parse_ini_string($content, true);
        }
    }
}
