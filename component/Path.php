<?php

namespace Component {

    class Path extends \RecursiveIteratorIterator {

        use Finder;

        public function __construct(string $path, int $mode = 0776, string $group = "www-data") {
            try {
                parent::__construct(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
            } catch (\Exception $ex) {
                if (!\is_dir($path) && \mkdir($path, $mode, true)) {
                    \chgrp($path, $group);
                    return self::__construct($path, $mode, $group);
                }

                throw $ex;
            }
        }
    }

}