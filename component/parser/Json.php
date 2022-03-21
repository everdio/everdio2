<?php
namespace Component\Parser {
    final class Json extends \Component\Parser {
        const EXTENSION = "json";
        final public function parse(string $content) : array {
            return (array) json_decode($content, true);
        }
    }
}
