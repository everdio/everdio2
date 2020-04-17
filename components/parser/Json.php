<?php
namespace Components\Parser {
    final class Json extends \Components\Parser {
        const EXTENSION = ".json";
        
        final static public function parse(string $content) : array {
            return (array) json_decode($content, true);
        }
    }
}
