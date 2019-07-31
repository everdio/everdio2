<?php
namespace Modules\Instance\Dom {
    class Xml extends \Modules\Instance\Dom {  
        public function __construct(string $file, string $version = NULL, string $encoding = NULL, int $options = LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NONET | LIBXML_NOWARNING) {
            parent::__construct($version, $encoding);
            $this->load($file, $options);         
        }
        
        public function display(\DOMNode $node = NULL) : string {
            return (string) $this->saveXML($node);
        }
    }
}