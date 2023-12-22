<?php

namespace Component\Caller {

    abstract class File extends \Component\Caller {

        public function __construct(string $_call, public string $file) {
            parent::__construct($_call);
        }

        public function getExtension(): string {
            return (string) \pathinfo($this->file, \PATHINFO_EXTENSION);
        }

        public function getBasename(string $extension = ""): string {
            return (string) \str_replace($extension, false, \pathinfo($this->file, \PATHINFO_BASENAME));
        }

        public function getPath(): string {
            return (string) $this->file;
        }

        public function getRealPath(): string {
            return (string) $this->file;
        }

        public function exists(): bool {
            return (bool) \is_file($this->file);
        }

        public function chmod(int $mode = 0775): void {
            \chmod($this->file, $mode);
        }

        public function chown(string $user = "www-data"): void {
            \chown($this->file, $user);
        }

        public function chgrp(string $group = "www-data"): void {
            \chgrp($this->file, $group);
        }

        public function delete() {
            return \unlink($this->file);
        }
    }

}