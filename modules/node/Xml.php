<?php
namespace Modules\Node {
    trait Xml {
        use \Modules\Node;
        protected function initialize() {
            if (!\array_key_exists(($hash = md5($this->document)), self::$adapters)) {
                \libxml_use_internal_errors(false);
                $dom = new \DOMDocument("1.0", "UTF-8");
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = false;      
                $dom->load($this->document, \LIBXML_PARSEHUGE | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);
                
                self::$adapters[$hash] = $dom;
            }
                
            
            return (object) self::$adapters[$hash];
        }             
    }
}