<?php
namespace Components\Resource\Dom {
    class Xml extends \Components\Resource\Dom {  
        public function __construct(string $string, string $version = NULL, string $encoding = NULL, int $options = NULL) {
            parent::__construct($version, $encoding);
            $this->loadXML($string, $options);         
        }
        
        public function display(\DOMNode $node = NULL) : string {
            return (string) $this->saveXML($node);
        }
        
        public function __dry() : string {
            return (string) sprintf("new \Components\Resource\Dom\Xml(\"%s\", \"%s\", \"%s\", LIBXML_NOERROR | LIBXML_NONET | LIBXML_NOWARNING)", addslashes($this->display()), $this->xmlVersion, $this->xmlEncoding);
        }        
    }
}