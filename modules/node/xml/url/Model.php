<?php

namespace Modules\Node\Xml\Url {

    class Model extends \Modules\Node\Model\Url {

        use \Modules\Node\Xml\Url;
                
        public function __construct(array $_parameters = []) {
            parent::__construct($_parameters);
            $this->use = "\Modules\Node\Xml\Url";
        }     
    }

}
