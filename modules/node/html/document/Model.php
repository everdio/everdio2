<?php

namespace Modules\Node\Html\Document {

    class Model extends \Modules\Node\Model\Document {

        use \Modules\Node\Html\Document;
        
        public function __construct(array $_parameters = []) {
            parent::__construct($_parameters);
            $this->use = "\Modules\Node\Html\Document";
        }        
    }

}
