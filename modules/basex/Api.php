<?php
/*
 * overriding Modules\Node query() and evaluate()
 */
namespace Modules\BaseX {
    trait Api {
        use \Modules\Node, \Modules\BaseX;       

        
        public function query(string $query) : \DOMNodeList {   
            if (isset($this->api)) {
                $api = new $this->api;

                foreach (\array_keys($api::$_queries) as $_query) {
                    $fragment = new \Modules\Node\Fragment($_query, $query);
                    if ($fragment->isValid()) {
                        $xpath = new \DOMXpath($api::$_queries[$_query]);
                        return (object) $xpath->query($fragment->execute());
                    }
                }         

                $api::$_queries[$query] = $api->getDOMDocument($query);

                $xpath = new \DOMXPath($api::$_queries[$query]);
                return (object) $xpath->query(\sprintf("//%s/*", $api->root)); 
            }
            
            throw new \LogicException("API does not exist");
        }    
        
        public function evaluate(string $query) : int {
            if (isset($this->api)) {
                $api = new $this->api;
                foreach (\array_keys($api::$_queries) as $_query) {
                    $fragment = new \Modules\Node\Fragment($_query, $query);
                    if ($fragment->isValid()) {
                        $xpath = new \DOMXpath($api::$_queries[$_query]);
                        return (int) $xpath->evaluate(\sprintf("count%s", $fragment->execute()));
                    }
                }
                
                return (int) $api->getResponse(\sprintf("count%s", $query));
            }
            
            throw new \LogicException("API does not exist");
        }         
    }
}