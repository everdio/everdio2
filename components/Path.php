<?php
namespace Components {
    use FilesystemIterator;
    use RecursiveIteratorIterator;
    class Path extends \RecursiveIteratorIterator {
        const SECRET = "s3cred";
        public function __construct($path, $create = true, $mode = 0776, $group = "www-data") {
            try {
                parent::__construct(new \RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
            } catch (\Exception $ex) {
                if ($create && mkdir($path, $mode, true)) {  
                   chgrp($path, $group);                   
                   return self::__construct($path, $mode, $group);
                }
                throw new Event($ex->getMessage());
            }
        }    
        
        public function destroy(string $secret = NULL) {
            if ($secret === self::SECRET) {
                foreach ($this as $path) {
                    if ($path->isDir()) {
                        rmdir($path);
                    } elseif($path->isFile()) {
                        unlink($path);
                    }
                }
            }
        }
    }
}