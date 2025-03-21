<?php

namespace Modules\Node\Html\Content {

    class Model extends \Modules\Node\Model\Content {

        use \Modules\Node\Html\Content;
        
        public function __construct(array $_parameters = []) {
            parent::__construct($_parameters);
            $this->use = "\Modules\Node\Html\Content";
        }                
    }

}
