<?php
namespace Modules\BaseX {
    /*
     * overriding Modules\Node query() and evaluate()
     */
    trait Api {
        use \Modules\Node, \Modules\BaseX;        
        public function query(string $query) : \DOMNodeList {     
            return (object) $this->api::construct()->query($query);
        }    
        
        public function evaluate(string $query) : int {
            return (int) $this->api::construct()->fetch(sprintf("count(%s)", $query));
        }           
    }
}