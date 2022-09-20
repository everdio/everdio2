<?php
namespace Modules\Node {
    final class Find extends \Component\Validation {
        public function __construct(string $xpath, array $validations = [], string $wrap = "(%s)") {
            $xparts = $parts = \explode(\DIRECTORY_SEPARATOR, \preg_replace('/\[(.*?)\]/', false, $xpath));
            foreach ($validations as $validation) {
                if ($validation instanceof \Component\Validation && $validation->isValid()) {    
                    if (\array_key_exists(($last = \array_key_last(\array_intersect(($fparts = \explode(\DIRECTORY_SEPARATOR, ($fpath = \preg_replace('/\[(.*?)\]/', false, ($filter = $validation->execute()))))), $parts))), $xparts)) {
                        $filter = \str_replace($fpath, false, $filter);
                        if (!\sizeof(\array_diff($fparts, $parts))) {    
                            $xparts[$last] .= $filter;                        
                        } elseif (\sizeof(\array_diff($fparts, $parts))) {                        
                            if ($validation instanceof Filter) {
                                $xparts[$last] .= \sprintf("[./%s]", \implode(\DIRECTORY_SEPARATOR, \array_diff($fparts, $parts)) . $filter);
                            } elseif ($validation instanceof Count) {
                                $xparts[$last] .= \sprintf("[%s]", \implode(\DIRECTORY_SEPARATOR, ($filter ? \array_diff(\explode(\DIRECTORY_SEPARATOR, $filter), $parts) : \array_diff($fparts, $parts))));    
                            }
                        }
                    }
                }
            }
            
            parent::__construct(\sprintf($wrap, \implode(\DIRECTORY_SEPARATOR, $xparts)), [new \Component\Validator\IsString\IsXPath]);
        }
    }
}

            
            