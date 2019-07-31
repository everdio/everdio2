<?php
namespace Modules {
    abstract class Dom extends \DOMDocument implements \Components\Core\Instance {
        use \Components\Dryer;
        use \Components\Helpers;
        public function __construct(string $version = null, string $encoding = null) {
            parent::__construct($version, $encoding);
            $this->preserveWhiteSpace = false;
            $this->formatOutput = false;               
        }
        
        public function execute($query, \DOMNode $node = NULL) : \DOMNodeList {
            $xpath = new \DOMXPath($this);
            return (object) $xpath->query($query, $node);                
        }   
        
        public function fetch(string $query) : array {
            
        }
        
        public function fetchAll(string $query) : array {
            foreach ($this->execute($query) as $node) {
                
            }
        }
        
        
        abstract public function display(\DOMNode $node = NULL) : string;
        
        public function __dry() : string {
            return (string) sprintf("new \%s(\"%s\", \"%s\", \"%s\", LIBXML_HTML_NOIMPLIED | LIBXML_NOCDATA | LIBXML_NOERROR | LIBXML_NONET | LIBXML_NOWARNING)", get_class($this), $this->documentURI, $this->xmlVersion, $this->xmlEncoding);            
        }          
    }
}