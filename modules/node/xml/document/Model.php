<?php

namespace Modules\Node\Xml\Document {

    class Model extends \Modules\Node\Model\Document {

        use \Modules\Node\Xml\Document;
                
        public function __construct(array $_parameters = []) {
            parent::__construct($_parameters);
            $this->use = "\Modules\Node\Xml\Document";
        }     
    }

}
