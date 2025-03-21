<?php

namespace Modules\Node\Html\Url {

    class Model extends \Modules\Node\Model\Url {

        use \Modules\Node\Html\Url;
        
        public function __construct(array $_parameters = []) {
            parent::__construct($_parameters);
            $this->use = "\Modules\Node\Html\Url";
        }
    }

}
