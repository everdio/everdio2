<?php

namespace Component\Caller {

    abstract class File extends \Component\Caller {

        public function __construct(string $_call, protected string $_file) {
            parent::__construct($_call);
        }

        public function getExtension(): string {
            return (string) \pathinfo($this->_file, \PATHINFO_EXTENSION);
        }

        public function getBasename(string $extension = ""): string {
            return (string) \str_replace($extension, false, \pathinfo($this->_file, \PATHINFO_BASENAME));
        }

        public function getPath(): string {
            return (string) $this->_file;
        }

        public function getRealPath(): string {
            return (string) $this->_file;
        }

        public function exists(): bool {
            return (bool) \is_file($this->_file);
        }

        public function chmod(int $mode = 0775): void {
            \chmod($this->_file, $mode);
        }

        public function chown(string $user = "www-data"): void {
            \chown($this->_file, $user);
        }

        public function chgrp(string $group = "www-data"): void {
            \chgrp($this->_file, $group);
        }

        public function delete() {
            return \unlink($this->_file);
        }

        public function __destruct() {
            $this->close();
        }
    }

}