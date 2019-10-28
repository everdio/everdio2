<?php
namespace Modules\Node {
    use \Components\Validator;
    final class Path extends \Components\Validation {
        public function __construct(string $npath, array $filters = [],  array $xpath = NULL, array $cpath = NULL) {
   
            foreach (explode(DIRECTORY_SEPARATOR, $npath) as $key => $part) {
                $cpath[$key] = $part;
                $xpath[$key] = $part;
                foreach ($filters as $filter) {
                    
                    if ($filter instanceof Filter && $filter->isValid()) {
                        $path = preg_replace('/\[(.*?)\]/', false, $filter->execute());
                        if (implode(DIRECTORY_SEPARATOR, $cpath) === $path) {
                            $xpath[$key] = $part . str_replace(implode(DIRECTORY_SEPARATOR, $cpath), false, $filter->execute());
                        } 
                    }
                }
            }
            
            parent::__construct(implode(DIRECTORY_SEPARATOR, $xpath), [new Validator\IsString\IsPath]);
        }
    }
}