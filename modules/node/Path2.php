<?php
namespace Modules\Node {
    use \Components\Validator;
    final class Path2 extends \Components\Validation {
        public function __construct(string $xpath, array $filters = []) {
            $xparts = $parts = explode(DIRECTORY_SEPARATOR, $xpath);
            
            echo "<PRE>";
            print_r($xparts);
            
            foreach ($filters as $filter) {
                if ($filter instanceof Filter && $filter->isValid()) {                   
                    $fparts = explode(DIRECTORY_SEPARATOR, ($fpath = preg_replace('/\[(.*?)\]/', false, ($filter = $filter->execute()))));                    
                    print_r($fparts);
                    $filter = str_replace($fpath, false, $filter);
                    $last = array_key_last(array_intersect($parts, $fparts));
                    if (!sizeof(array_diff($fparts, $parts))) {    
                        $xparts[$last] .= $filter;                        
                    } elseif (sizeof(array_diff($fparts, $parts))) {                        
                        $xparts[$last] .= sprintf("[./%s]", implode(DIRECTORY_SEPARATOR, array_diff($fparts, $parts)) . $filter);
                    }
                }
            }
            
            parent::__construct(implode(DIRECTORY_SEPARATOR, $xparts), [new Validator\IsString\IsPath]);
        }
    }
}

            
            