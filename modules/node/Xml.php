<?php
namespace Modules\Node {
    trait Xml {
        use \Modules\Node;
        
        protected function initialize() : object {
            if (!\array_key_exists($this->document, self::$_adapters)) {
                $xml = new \DOMDocument("1.0", "UTF-8");
                $xml->preserveWhiteSpace = false;
                $xml->substituteEntities = false;
                $xml->formatOutput = false; 
                $xml->recover = true;
                $xml->load($this->document, \LIBXML_PARSEHUGE | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);
                
                self::$_adapters[$this->document] = $xml;
            }
            return (object) self::$_adapters[$this->document];
        }
    }
}