<?php

namespace Modules\Node\Xml\Content {

    class Model extends \Modules\Node\Model\Content {

        use \Modules\Node\Xml\Content;
        
        public function __construct(array $_parameters = []) {
            parent::__construct($_parameters);
            $this->use = "\Modules\Node\Xml\Content";
        }            
    }

}
