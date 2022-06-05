<?php
namespace Modules\BaseX {
    trait Api {
        use \Modules\Node, \Modules\BaseX;
        public function query(string $query) : \DOMNodeList {      
            $api = new $this->api;
            foreach (\array_keys($api::$_queries) as $_query) {
                if (\str_contains($query, $_query)) {                    
                    return (object) $api::$_queries[$_query]->query($query);
                }
            }       
            
            $api::$_queries[$query] = new \DOMXPath($api->fetchDom($query));
            return (object) $api::$_queries[$query]->query(\sprintf("//%s/*", $api->root));
        }             
    }
}