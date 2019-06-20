<?php
namespace Components\Resource {
    abstract class Dom extends \DOMDocument {
        use \Components\Dryer;
        use \Components\Helpers;
        use \Components\Resource;
        
        public function __construct(string $version = null, string $encoding = null) {
            parent::__construct($version, $encoding);
            $this->preserveWhiteSpace = false;
            $this->formatOutput = true;               
        }
        
        public function execute($query, \DOMNode $node = NULL) : \DOMNodeList {
            $xpath = new \DOMXPath($this);
            return (object) $xpath->query($query, $node);                
        }   
        
        abstract public function display(\DOMNode $node = NULL) : string;
    }
}