<?php
namespace Modules\Everdio {
    class Account extends \Modules\Everdio\Library\ECms\Account {
        public function save(string $realm) {
            if (isset($this->AccountId) && !empty($this->Password)) {
                $this->Password =  md5(sprintf("%s:%s:%s", $this->Account, $realm, $this->Password));
            } else {
                $this->remove("Password");
            }
            
            parent::save();
        }
    }
}