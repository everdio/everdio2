<?php
namespace Component {
    abstract class Parser {
        use Dryer;
        const EXTENSION = self::EXTENSION;
        
        abstract static public function parse(string $content) : array;
        
        public function __dry() : string {
            return (string) sprintf("new %s", get_class($this));
        }
    }
}
