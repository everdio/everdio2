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
                return (object) $api::$_queries[$query]->query(\sprintf("//%s/*", $api->root));                                
            } else {
                foreach (\array_keys($api::$_queries) as $_query) {
                    if (\str_contains(\str_replace(["(", ")"], false, $query), \str_replace(["(", ")"], false, $_query))) {
                        return (object) $api::$_queries[$_query]->query(\sprintf("(%s)", \preg_replace("/\[(.*?)\]/", false, \str_replace(["(", ")"], false, $_query)) . \str_replace(\str_replace(["(", ")"], false, $_query), false, \str_replace(["(", ")"], false, $query))));
                    }
                } 
            }
            
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->preserveWhiteSpace = false;
            $dom->substituteEntities = false;
            $dom->formatOutput = false; 
            $dom->recover = true;
            $dom->loadXML(\sprintf("<%s>%s</%s>", $api->root, $api->query($query), $api->root), \LIBXML_PARSEHUGE | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS);
            
            $api::$_queries[$query] = new \DOMXPath($dom);
            return (object) $api::$_queries[$query]->query(\sprintf("//%s/*", $api->root));              
        }    
        
        public function evaluate(string $query) : int {
            return (int) $this->api::construct()->query(\sprintf("count%s", $query));
        }         
    }
}