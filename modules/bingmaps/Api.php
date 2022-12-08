<?php
namespace Modules\Bingmaps {
    trait Api {
        use \Modules\Node, \Modules\Bingmaps;
        public function query(string $query) : \DOMNodeList {
            return (object) $this->xpath($this->request::construct(["point" => $this->point])->fetch($query))->query($query);
        }
    }
}