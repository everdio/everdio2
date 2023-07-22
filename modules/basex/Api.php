<?php
/*
 * overriding Modules\Node query() and evaluate()
 */
namespace Modules\BaseX {
    trait Api {
        use \Modules\Node, \Modules\BaseX;
        public function query(string $query) : \DOMNodeList {   
            $api = new $this->api;
            foreach ($api::$_queries as $_query => $dom) {
                $fragment = new \Modules\Node\Fragment($_query, $query);
                if ($fragment->isValid()) {
                    $xpath = new \DOMXpath($dom);
                    return (object) $xpath->query($fragment->execute());
                }
            }         
            
            $api::$_queries[$query] = $api->getDOMDocument($query);
            $xpath = new \DOMXPath($api::$_queries[$query]);
            return (object) $xpath->query(\sprintf("//%s/*", $api->root)); 
        }    
        
        public function evaluate(string $query) : int {
            $api = new $this->api;
            foreach ($api::$_queries as $_query => $dom) {
                $fragment = new \Modules\Node\Fragment($_query, $query);
                if ($fragment->isValid()) {
                    $xpath = new \DOMXpath($dom);
                    return (int) $xpath->evaluate("count" . $fragment->execute());
                }
            }

            return (int) $api->getResponse("count" . $query);
        }        
    }
}