<?php
namespace Components\Adapter\Dom {
    class Xml extends \Components\Adapter\Dom {  
        public function __construct(string $string, string $version = NULL, string $encoding = NULL, int $options = LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NONET | LIBXML_NOWARNING) {
            parent::__construct($version, $encoding);
            $this->loadXML($string, $options);         
        }
        
        public function display(\DOMNode $node = NULL) : string {
            return (string) $this->saveXML($node);
        }
        
        public function __dry() : string {
            return (string) sprintf("new \Components\Adapter\Dom\Xml(stripslashes(\"%s\"), \"%s\", \"%s\", LIBXML_NOCDATA | LIBXML_NOERROR | LIBXML_NONET | LIBXML_NOWARNING)", addslashes($this->display()), $this->xmlVersion, $this->xmlEncoding);
        }        
    }
}