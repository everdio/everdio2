<?php
namespace Modules\Node {
    final class Fragment extends \Component\Validation {
        public function __construct(string $xpath, string $xfragment, string $wrap = "(%s)") {   
            if (\str_contains($xfragment, $xpath)) {
                $xparts = \array_filter(\explode(\DIRECTORY_SEPARATOR, \preg_replace("/\[(.*?)\]/", false, $xpath)));
                $first = \reset($xparts);
                $last = \end($xparts);
                parent::__construct(\sprintf($wrap, \sprintf("/%s/%s%s", $first, $last, \str_replace($xpath, false, $xfragment))), [new \Component\Validator\IsString\IsXPath]);
            }
        }
    }
}

            
            