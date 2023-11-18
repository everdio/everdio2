<?php

namespace Modules\Node {

    final class Find extends \Component\Validation {

        public function __construct(string $xpath, array $validations = [], string $wrap = "(%s)") {
            $xparts = $parts = \explode(\DIRECTORY_SEPARATOR, $this->clean($xpath));
            foreach ($validations as $validation) {
                if ($validation instanceof \Component\Validation && $validation->isValid()) {
                    if (\array_key_exists(($last = \array_key_last(\array_intersect(($fparts = \explode(\DIRECTORY_SEPARATOR, ($fpath = $this->clean(($filter = $validation->execute()))))), $parts))), $xparts)) {
                        $filter = \str_replace($fpath, false, $filter);
                        if (!\sizeof(\array_diff($fparts, $parts))) {
                            $xparts[$last] .= $filter;
                        } elseif (\sizeof(\array_diff($fparts, $parts))) {
                            $xparts[$last] .= \sprintf("[%s]", \implode(\DIRECTORY_SEPARATOR, \array_diff($fparts, $parts)) . $filter);
                        }
                    }
                }
            }
    
            parent::__construct(\sprintf($wrap, \implode(\DIRECTORY_SEPARATOR, $xparts)), [new \Component\Validator\IsString]);
            //parent::__construct(\sprintf($wrap, \str_replace("][", \sprintf(" %s ", $operator), \implode(\DIRECTORY_SEPARATOR, $xparts))), [new \Component\Validator\IsString]);
            
        }
        
        public function clean(string $xpath): string {
            return (string) \preg_replace("/\[(.*?)\]/", false, $xpath);
        }        
    }

}