<?php
namespace Components\Resource\Dom {
    class Html extends \Components\Resource\Dom {      
        public function __construct(string $string, string $version = NULL, string $encoding = NULL, int $options = NULL) {
            parent::__construct($version, $encoding);
            $this->loadHTML($string, $options);         
        }        
        
        public function display(\DOMNode $node = NULL) : string {
            return (string) $this->saveHTML($node);
        }
        
        public function __dry() : string {
            return (string) sprintf("new \Components\Resource\Dom\Html(\"%s\", \"%s\", \"%s\", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NONET | LIBXML_NOWARNING)", addslashes($this->display()), $this->xmlVersion, $this->xmlEncoding);
        }                
    }
}