<?php
namespace Component\Caller\File {
    class Fopen extends \Component\Caller\File {
        public function __construct(string $_file, string $mode = NULL, bool $use_include_path = false, $context = NULL) {
            parent::__construct("f", $_file);
            $this->resource = $this->open($_file, $mode, $use_include_path, $context);
        }

        public function getSize() : int {
            $this->seek(0, \SEEK_END);
            return (int) $this->tell();
        }

        public function __destruct() {
            $this->close();
        }
    }
}