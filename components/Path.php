<?php
namespace Components {
    use FilesystemIterator;
    use RecursiveIteratorIterator;
    class Path extends \RecursiveIteratorIterator {
        public function __construct($path, $mode = 0776, $group = "www-data") {
            try {
                parent::__construct(new \RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
            } catch (\Exception $ex) {
                if (mkdir($path, $mode, true)) {  
                   chgrp($path, $group);                   
                   return self::__construct($path, $mode, $group);
                }
                
                throw new Event($ex->getMessage());
            }
        }    
    }
}