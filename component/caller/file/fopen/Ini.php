<?php

namespace Component\Caller\File\Fopen {

    class Ini extends \Component\Caller\File\Fopen {

        public function __construct(string $path, string $mode = "r") {
            parent::__construct(\sprintf("%s.ini", $path), $mode);
        }

        final public function writeSection(string $section) {
            parent::write(\sprintf("[%s]\n", $section));
        }

        final public function writePair(string $parameter, $value) {
            parent::write(\sprintf("%s = %s;\n", $parameter, $this->dehydrate($value)));
        }

        final public function writeKeyPair(string|int $section, array $values) {
            foreach ($values as $key => $value) {
                parent::write(\sprintf("%s[%s] = %s;\n", $section, $key, $this->dehydrate($value)));
            }
        }

        final public function write(array $array, string|null $section = null) {
            if ($section) {
                $this->writeSection($section);
            }

            foreach ($array as $key => $value) {
                if (\is_array($value)) {
                    $this->writeKeyPair($key, $value);
                } else {
                    $this->writePair($key, $value);
                }
            }
        }

        final public function read(): array {
            return (array) \parse_ini_string(parent::read(\filesize($this->file)), true, \INI_SCANNER_TYPED);
        }
    }

}