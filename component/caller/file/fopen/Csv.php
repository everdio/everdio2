<?php

namespace Component\Caller\File\Fopen {

    class Csv extends \Component\Caller\File\Fopen {

        public function read(array $keys, array $rows = []): array {
            while (!$this->eof() && ($data = $this->getcsv())) {
                $rows[] = \array_combine($keys, $data);
            }

            return (array) $rows;
        }
    }

}