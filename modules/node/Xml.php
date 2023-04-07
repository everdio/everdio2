<?php
namespace Modules\Node {
    trait Xml {
        use \Modules\Node;

        protected function __init() : object {
            $xml = new \DOMDocument("1.0", "UTF-8");
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = false; 
            $xml->recover = true;
            $xml->substituteEntities = false;                
            $xml->load($this->document, \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);            
            return (object) $xml;
        }
    }
}