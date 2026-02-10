<?php

namespace Component\Caller {

    class Dir extends \Component\Caller {

        public function __construct() {
            parent::__construct("%sdir");
        }

        final public function create(string $dir, $permissions = 0775, bool $recursive = true): bool {
            return (bool) (!\is_dir($dir) && \mkdir($dir, $permissions, $recursive)) && $this->open($dir);
        }

        final public function open($dir): bool {
            return (bool) $this->handle = \opendir($dir);
        }

        final public function remove(array $exclude = [".", ".."]): void {
            foreach (\array_diff($this->scan(), $exclude) as $entry) {
                if (\is_dir($entry)) {
                    $dir = new self;
                    $dir->open($entry);
                    //$dir->remove();
                } else {
                    //\unlink($entry);
                }
            }
        }
    }

}