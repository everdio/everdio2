<?php
namespace Modules\Node {
    use \Components\Validator;
    final class Path extends \Components\Validation {
        public function __construct(string $xpath, array $filters = [], string $wrap = "%s") {
            $xparts = $parts = explode(DIRECTORY_SEPARATOR, preg_replace('/\[(.*?)\]/', false, $xpath));
            foreach ($filters as $filter) {
                if ($filter instanceof \Components\Validation && $filter->isValid()) {    
                    if (array_key_exists(($last = array_key_last(array_intersect(($fparts = explode(DIRECTORY_SEPARATOR, ($fpath = preg_replace('/\[(.*?)\]/', false, ($filter = $filter->execute()))))), $parts))), $xparts)) {
                        $filter = str_replace($fpath, false, $filter);
                        if (!sizeof(array_diff($fparts, $parts))) {    
                            $xparts[$last] .= $filter;                        
                        } elseif (sizeof(array_diff($fparts, $parts))) {                        
                            $xparts[$last] .= sprintf("[./%s]", implode(DIRECTORY_SEPARATOR, array_diff($fparts, $parts)) . $filter);
                        }
                    }
                }
            }
            

            parent::__construct(sprintf($wrap, DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $xparts)), [new Validator\IsString\IsPath]);
        }
    }
}

            
            