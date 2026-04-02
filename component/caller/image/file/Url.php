<?php

namespace Component\Caller\Image\File {

    class Url extends \Component\Caller\Image\File {

        public function __construct(string $url) {
            $file = new \Component\Caller\File\Fopen(\sys_get_temp_dir() . \DIRECTORY_SEPARATOR . \basename($url), "c+");

            $curl = new \Component\Caller\Curl([
                \CURLOPT_URL => $url,
             ]);
            $curl->get($file());
            $curl->execute();

            parent::__construct($file->getPath());

            $file->delete();
        }
    }

}