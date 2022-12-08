<?php
namespace Modules\BaseX {
    /*
     * overriding Modules\Node query() and evaluate()
     */
    trait Api {
        use \Modules\Node, \Modules\BaseX;        
        public function query(string $query) : \DOMNodeList {     
            if (isset($this->api)) {
                return (object) $this->api::construct()->query($query);
            }
            
            throw new \LogicException("unknown or invalid API");
        }    
        
        public function evaluate(string $query) : int {
            if (isset($this->api)) {
                return (int) $this->api::construct()->fetch(sprintf("count(%s)", $query));
            }            
            
            throw new \LogicException("unknown or invalid API");
            
        }           
    }
}