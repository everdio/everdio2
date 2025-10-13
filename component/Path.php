<?php

namespace Component {

    class Path extends \RecursiveIteratorIterator {

        use Finder;

        public function __construct(string $path, int $mode = 0770, string $group = "www-data") {
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

        public function delete(array $extensions, bool $recursive = false): void {
            while ($this->valid()) {
                if (!$this->isDir() && $this->isFile() && \in_array(\strtolower($this->getExtension()), $extensions)) {
                    \unlink($this->getRealPath());
                } elseif ($recursive && $this->isDir()) {
                    $path = new Path($this->getRealPath());
                    $path->delete($extensions, $recursive);
                    $path->rewind();
                    if (!$path->valid()) {
                        \rmdir($this->getRealPath());
                    }
                }

                $this->next();
            }
        }
    }

}