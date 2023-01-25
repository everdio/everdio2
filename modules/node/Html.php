<?php
namespace Modules\Node {
    trait Html {
        use \Modules\Node;
        protected function __init() : object {
            $html = new \DOMDocument("1.0", "UTF-8");
            $html->preserveWhiteSpace = false;
            $html->formatOutput = false; 
            $html->recover = true;
            $html->substituteEntities = false;                
            $html->loadHTMLFile($this->document, \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);
            return (object) $html;
        }
    }
}