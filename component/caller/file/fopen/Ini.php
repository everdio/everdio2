<?php
namespace Component\Caller\File\Fopen {
    class Ini extends \Component\Caller\File\Fopen {
        public function __construct(string $path) {
            parent::__construct(\sprintf("%s.ini", $path), "c");
        }
        
        public function writeSection(string $section) {
            $this->write(\sprintf("[%s]\n", $section));
        }
        
        public function writePair(string $parameter, $value) {
            $this->write(\sprintf("%s = %s;\n", $parameter, $this->dehydrate($value)));
        }
        
        public function writePairArray(array $array) {
            foreach ($array as $parameter => $value) {
                $this->writePair($parameter, $value);
            }
        }
        
        public function writeKeyPair(string $key, string $parameter, $value) {
            $this->write(\sprintf("%s[%s] = %s;\n", $key, $parameter, $this->dehydrate($value)));
        }
        
        public function writeKeyPairArray(array $array) {
            foreach ($array as $key => $values) {
                foreach ($values as $parameter => $value) {
                    $this->writeKeyPair($key, $parameter, $value);  
                }
            }
        }
    }
}