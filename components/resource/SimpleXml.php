<?php
namespace Components\Resource {    
    class SimpleXML {
        use \Components\Dryer;
        use \Components\Helpers;
        use \Components\Resource;
        
        private $xml = false;
        
        public function __construct(\SimpleXMLElement $xml) {
            $this->xml = $xml;
        }
        
        public function execute(string $query) : array {
            return (array) $this->xpath($query);
        }
        
        public function __dry() : string {
            return (string) sprintf("new \Resource\SimpleXML")
        }
    }
}
