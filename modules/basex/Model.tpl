<?php
namespace {{namespace}} {
    class {{class}} extends \Component\Core\Adapter {
        use {{use}};  
        
        static private $_queries = [];
        
        public function __construct() {
            parent::__construct({{mapper}});
        }       

        static public function construct() : self {
            return (object) new {{class}}();
        }        
        
        public function query(string $query) : \DOMNodelist {
            foreach (\array_keys(self::$_queries) as $_query) {
                if (\str_contains(\str_replace(["(", ")"], false, $query), \str_replace(["(", ")"], false, $_query))) {      
                    return (object) self::$_queries[$_query]->query($query);
                }
            } 
            
            self::$_queries[$query] = new \DOMXPath($this->fetchDom($query));
            return (object) self::$_queries[$query]->query(\sprintf("//%s/*", $this->root));            
        }
    }
}