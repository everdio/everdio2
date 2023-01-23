<?php
namespace Modules\BaseX {
    /*
     * overriding Modules\Node query() and evaluate()
     */
    trait Api {
        use \Modules\Node, \Modules\BaseX;                      
      
        public function query(string $query) : \DOMNodeList {     
            $api = new $this->api;
            
            if (\array_key_exists($query, $api::$_queries)) {
                $xpath = new \DOMXpath($api::$_queries[$query]);
                return (object) $xpath->query(\sprintf("//%s/*", $api->root));                                
            } else {
                foreach (\array_keys($api::$_queries) as $_query) {
                    $fragment = new \Modules\Node\Fragment(str_replace(["(", ")"], false, $_query), \str_replace(["(", ")"], false, $query));
                    if ($fragment->isValid()) {
                        $xpath = new \DOMXpath($api::$_queries[$_query]);
                        return (object) $xpath->query(\sprintf("%s", $fragment->execute()));
                    }
                } 
            }
            
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false; 
            $dom->loadXML(\sprintf("<%s>%s</%s>", $api->root, $api->query($query), $api->root), \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);            
            
            $api::$_queries[$query] = $dom;
            
            $xpath = new \DOMXPath($api::$_queries[$query]);
            return (object) $xpath->query(\sprintf("//%s/*", $api->root)); 
        }    
        
        public function evaluate(string $query) : int {
            $api = new $this->api;
            
            if (\array_key_exists($query, $api::$_queries)) {
                $xpath = new \DOMXpath($api::$_queries[$query]);
                return (int) $xpath->evaluate(\sprintf("count(//%s/*)", $api->root));                                
            } else {
                foreach (\array_keys($api::$_queries) as $_query) {
                    $fragment = new \Modules\Node\Fragment(str_replace(["(", ")"], false, $_query), \str_replace(["(", ")"], false, $query));
                    if ($fragment->isValid()) {
                        $xpath = new \DOMXpath($api::$_queries[$_query]);
                        return (int) $xpath->evaluate(\sprintf("count%s", $fragment->execute()));
                    }
                } 
            }            
            
            return (int) $this->api::construct()->query(\sprintf("count%s", $query));
        }         
    }
}