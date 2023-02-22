<?php
namespace Component\Caller\File\Fopen {
    class Ini extends \Component\Caller\File\Fopen {
        public function __construct(string $path) {
            parent::__construct(\sprintf("%s.ini", $path), "c");
        }
        
        public function writeSection(string $section) {
            $this->write(\sprintf("[%s]\n", $section));
        }
        
        public function writeValue($value) : string {
            return (string) \addcslashes(\trim(\trim($this->dehydrate($value), "'")), "\"");
        }
        
        public function writePair(string $parameter, $value) {
            $this->write(\sprintf("%s = \"%s\";\n", $parameter, $this->writeValue($value)));
        }

        public function writeKeyPair(string $key, string $parameter, $value) {
            $this->write(\sprintf("%s[%s] = \"%s\";\n", $key, $parameter, $this->writeValue($value)));
        }

        public function writeArray(array $array) {
            foreach ($array as $key1 => $value1) {
                if (\is_array($value1)) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->writeKeyPair($key1, $key2, $value2);
                    }
                } else {
                    $this->writePair($key1, $value1);
                }
            }
        }
    }
}