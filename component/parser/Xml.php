<?php
namespace Component\Parser {
    final class Xml extends \Component\Parser {
        const EXTENSION = "xml";     
        private $_parser = false;
        public function __construct() {
            $this->_parser = xml_parser_create();
            xml_set_object($this->_parser, $this);
            xml_set_element_handler($this->_parser, "begin", "end");            
        }
        
        private function begin($parser, $name, $attributes, $value) {
            print_r($parser);
            print_r($name);
            print_r($attributes);
            print_r($value);
            ob_flush();
            flush();
        }
        
        private function end($parser, $name) {
            
        }
        
        final public function read($file) {
            $file = new \Component\File($file, "r");
            while (!feof($file)) {
                $data = fread($file, 4096);
                xml_parse($this->_parser, $data, feof($file));
            }            
        }
        
        final public function parse(string $content) : array {
            xml_parse($this->_parser, $content);
        }
    }
}
