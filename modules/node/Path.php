<?php
namespace Modules\Node {
    use \Components\Validator;
    final class Path extends \Components\Validation {
        public function __construct(string $xpath, array $filters = []) {
            $xparts = $parts = explode(DIRECTORY_SEPARATOR, $xpath);
            
            foreach ($filters as $filter) {
                if ($filter instanceof Filter && $filter->isValid()) {                   
                    $fparts = explode(DIRECTORY_SEPARATOR, ($fpath = preg_replace('/\[(.*?)\]/', false, ($filter = $filter->execute()))));                                        
                    $filter = str_replace($fpath, false, $filter);
                    $last = array_key_last(array_intersect($fparts, $parts));
                    if (!sizeof(array_diff($fparts, $parts))) {    
                        $xparts[$last] .= $filter;                        
                    } elseif (sizeof(array_diff($fparts, $parts))) {                        
                        $xparts[$last] .= sprintf("[./%s]", implode(DIRECTORY_SEPARATOR, array_diff($fparts, $parts)) . $filter);
                    }
                }
            }
            
            parent::__construct(DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $xparts), [new Validator\IsString\IsPath]);
        }
    }
}

            
            