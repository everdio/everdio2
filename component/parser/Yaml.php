<?php
namespace Component\Parser {
    final class Yaml extends \Component\Parser {
        const EXTENSION = "yml";          
        final public function parse(string $content) : array {
            return (array) parse_yaml($content);
        }
    }
}
