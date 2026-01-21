<?php

namespace Component\Caller {

    class Dir extends \Component\Caller {

        public function __construct() {
            parent::__construct("%sdir");
        }

        final public function create(string $dir, int $permissions = 0776, bool $recursive = true): bool {
            return (bool) (!\is_dir($dir) && \mkdir($dir, $permissions, $recursive)) && $this->open($dir);
        }

        final public function open($dir): bool {
            return (bool) $this->handle = \opendir($dir);
        }
    }

}