<?php

namespace Component {

    class Path extends \RecursiveIteratorIterator {

        use Finder;

        public function __construct(string $path, int $mode = 0776, string $group = "www-data") {
            try {
                parent::__construct(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
            } catch (\UnexpectedValueException $ex) {
                if (!\is_dir($path) && \mkdir($path, $mode, true)) {
                    \chgrp($path, $group);
                    return self::__construct($path, $mode, $group);
                }

                throw $ex;
            }
        }

        public function delete(array $extensions = []): void {
            while ($this->valid()) {
                if ($this->isFile() && \in_array($this->getExtension(), $extensions)) {
                    \unlink ($this->getRealPath());
                } elseif ($this->isDir()) {
                    (new Path($this->getRealPath()))->delete($extensions);
                }
            }
        }
    }

}