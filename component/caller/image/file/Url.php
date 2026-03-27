<?php

namespace Component\Caller\Image\File {

    class Url extends \Component\Caller\Image\File {

        public function __construct(string $url) {
            $file = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . \basename($url);
            if (\file_put_contents($file, \file_get_contents($url))) {
                parent::__construct($file);
            }

            \unlink($file);
        }
    }

}