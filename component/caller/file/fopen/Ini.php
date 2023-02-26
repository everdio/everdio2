<?php
namespace Component\Caller\File\Fopen {
    class Ini extends \Component\Caller\File\Fopen {
        public function __construct(string $path) {
            parent::__construct(\sprintf("%s.ini", $path), "c");
        }
        
        final public function writeSection(string $section) {
            if (!empty($section)) {
                $this->write(\sprintf("[%s]\n", $section));
            }
        }
        
        private function _value($value) : string {
            return (string) \addcslashes(\trim(\trim($this->dehydrate($value), "'")), "\"");
        }
        
        final public function writePair(string $parameter, $value) {
            if (!empty($parameter)) {
                $this->write(\sprintf("%s = \"%s\";\n", $parameter, $this->_value($value)));
            }
        }

        final public function writeKeyPair(string $key, string $parameter, $value) {
            if (!empty($key) && !empty($parameter)) {
                $this->write(\sprintf("%s[%s] = \"%s\";\n", $key, $parameter, $this->_value($value)));
            }
        }

        final public function writeArray(array $array1) {
            foreach ($array1 as $key1 => $value1) {
                if (\is_array($value1)) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->writeKeyPair($key1, $key2, $value2);
                    }
                } else {
                    $this->writePair($key1, $value1);
                }
            }
        }
        
        final public function store(string $section, array $array) {
            $this->writeSection($section);
            $this->writeArray($array);
        }
    }
}