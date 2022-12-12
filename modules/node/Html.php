<?php
namespace Modules\Node {
    trait Html {
        use \Modules\Node;
        protected function initialize() {
            if (!\array_key_exists(($hash = md5($this->document)), self::$_adapters)) {
                $html = new \DOMDocument("1.0", "UTF-8");
                $html->preserveWhiteSpace = false;
                $html->formatOutput = false; 
                $html->recover = true;
                $html->loadHTMLFile($this->document, \LIBXML_PARSEHUGE | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);
                
                self::$_adapters[$hash] = $html;
            }
            
            return (object) self::$_adapters[$hash];
        }        
    }
}