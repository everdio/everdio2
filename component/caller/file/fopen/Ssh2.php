<?php

namespace Component\Caller\File\Fopen {

    class Ssh2 extends \Component\Caller\File\Fopen {

        public function __construct(string $file, string $mode = "r") {
            parent::__construct("ssh2.sftp://" . $file, $mode);
        }
    }

}