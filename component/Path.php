<?php

namespace Component {

    class Path extends \RecursiveIteratorIterator {

        public function __construct(string $path, int $mode = 0770) {
            try {
                parent::__construct(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
            } catch (\UnexpectedValueException $ex) {
                if (!\is_dir($path) && \mkdir($path, $mode, true)) {
                    return self::__construct($path, $mode);
                }

                throw $ex;
            }
        }
    }

}