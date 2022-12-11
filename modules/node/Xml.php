<?php
namespace Modules\Node {
    trait Xml {
        use \Modules\Node;
        protected function initialize() {
            if (!\array_key_exists(($hash = md5($this->document)), self::$_adapters)) {
                \libxml_use_internal_errors(false);
                $xml = new \DOMDocument("1.0", "UTF-8");
                $xml->preserveWhiteSpace = false;
                $xml->formatOutput = false;     
                $xml->recover = true;
                $xml->load($this->document, \LIBXML_PARSEHUGE | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);
                
                self::$_adapters[$hash] = $xml;
            }
                
            
            return (object) self::$_adapters[$hash];
        }             
    }
}