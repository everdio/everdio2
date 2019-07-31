<?php
namespace Modules {
    trait Dsn {
        use \Components\Dryer;
        use \Components\Helpers;        
        private $dsn, $username, $password;
        public function __dry() : string {
            return (string) sprintf("new \%s(%s, %s, %s)", get_class($this), $this->dehydrate($this->dsn), $this->dehydrate($this->username), $this->dehydrate($this->password));
        }
    }
}
