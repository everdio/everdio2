<?php

namespace Component\Caller\File\Fopen {

    class Csv extends \Component\Caller\File\Fopen {

        public function read(array $keys, int $length = 0, string $separator = ",", string $enclosure = "\"", string $escape = "\\", array $rows = []): array {
            while (!$this->eof() && ($data = $this->getcsv($length, $separator, $enclosure, $escape))) {
                $rows[] = \array_combine($keys, $data);
            }

            return (array) $rows;
        }
    }

}