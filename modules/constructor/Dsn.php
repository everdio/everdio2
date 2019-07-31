<?php
namespace Modules\Constructor {
    use \Components\Validation;
    use \Components\Validator;    
    class Dsn extends \Components\Core\Adapter\Constructor {
        public function __construct(string $construct, string $dsn, string $username, string $password) {
            $this->add("dsn", new Validation($dsn, [new Validator\IsString]));
            $this->add("username", new Validation($username, [new Validator\IsString]));
            $this->add("password", new Validation($password, [new Validator\IsString]));
            parent::__construct($construct);
        }
        
        public function __dry() : string {
            return (string) sprintf("new %s(\"%s\", \"%s\", \"%s\")", $this->construct, $this->dsn, $this->username, $this->password);
        }        
    }
}

