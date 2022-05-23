<?php
namespace Component {
    use FilesystemIterator, RecursiveIteratorIterator;
    class Path extends \RecursiveIteratorIterator {
        public function __construct(string $path, int $mode = 0776, string $group = "www-data") {
            try {
                parent::__construct(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
            } catch (\Exception $ex) {
                if (\mkdir($path, $mode, true)) {  
                    \chgrp($path, $group);                   
                    return self::__construct($path, $mode, $group);
                }
                
                throw $ex;
            }
        }    
        
        static public function construct(string $path, int $mode = 0776, string $group = "www-data") : self {
            return (object) new Path($path, $mode, $group);
        }
    }
}