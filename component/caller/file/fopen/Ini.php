<?php
namespace Component\Caller\File\Fopen {
    class Ini extends \Component\Caller\File\Fopen {
        public function __construct(string $path, string $mode = "r") {
            parent::__construct(\sprintf("%s.ini", $path), $mode);
        }
        
        private function _prepare($value) : string {
            return (string) \addcslashes(\trim(\trim($this->dehydrate($value), "'")), "\"");
        }

        private function _writeSection(string $section) {
            $this->write(\sprintf("[%s]\n", $section));
        }        
        
        private function _writePair(string $parameter, $value) {
            $this->write(\sprintf("%s = \"%s\";\n", $parameter, $this->_prepare($value)));
        }

        private function _writeKeyPair(string | int $key, string | int $parameter, $value) {
            $this->write(\sprintf("%s[%s] = \"%s\";\n", $key, $parameter, $this->_prepare($value)));
        }
        
        final public function store(string $section, array $data) {
            $this->_writeSection($section);
            foreach ($data as $key1 => $value1) {
                if (\is_array($value1)) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->_writeKeyPair($key1, $key2, $value2);
                    }
                } else {
                    $this->_writePair($key1, $value1);
                }
            }
        }
    }
}