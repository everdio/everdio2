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
            
            throw new \RuntimeException("unknown or invalid API");
        }      
        
        public function evaluate(string $query) : int {
            return (int) $this->query($query)->length;
        }                
    }
}