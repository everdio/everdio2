<?php
namespace Modules\Node {
    final class Fragment extends \Component\Validation {
        /*
         * to create incremental xpaths (trailing fragment from a (previous) xpath) in order to re-use existing output
         */
        public function __construct(string $xpath, string $xfragment, string $wrap = "(%s)") {   
            if (\str_contains(($xfragment = $this->_clean($xfragment)), ($xpath = $this->_clean($xpath)))) {
                $xparts = \array_filter(\explode(\DIRECTORY_SEPARATOR, \preg_replace("/\[(.*?)\]/", false, $xpath)));
                $first = \reset($xparts);
                $last = \end($xparts);
             
                parent::__construct(\sprintf($wrap, \sprintf("/%s/%s%s", $first, $last, \str_replace($xpath, false, $xfragment))), [new \Component\Validator\IsString\IsXPath]);
            }
        }
        
        /*
         *  removing anything outside the outer () and and trimes the () away completely; clean xpath
         */
        private function _clean(string $xpath) : string {
            
            return (string) \trim(\str_replace(preg_replace("/\(.*\)/", false, $xpath), false, $xpath), "()");
        }
    }
}

            
            