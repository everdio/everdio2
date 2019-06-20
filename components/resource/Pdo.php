<?php
namespace Components\Resource {   
    class Pdo extends \PDO {
        use \Components\Dryer;
        use \Components\Helpers;
        use \Components\Resource;
        
        private $dsn, $username, $password;      
        public function __construct($dsn, $username, $password) {
            $this->dsn = $dsn;
            $this->username = $username;
            $this->password = $password;
            
            try {
                parent::__construct($this->dsn, $this->username, $this->password);    
            } catch (\PDOException $ex) {
                throw new Event($ex->getMessage());
            }
        }

        public function execute(string $query) :  \PDOStatement {
            $stm = $this->prepare($query);
            
            if ($stm->execute()) {
                return (object) $stm;
            }
            
            throw new Event(sprintf("%s in %s", $this->dehydrate($stm->errorInfo()), $this->substring($query, 0, 100, false, "...")));
        }
        
        public function __dry() : string {
            return (string) sprintf("new \Components\Resource\Pdo(\"%s\", \"%s\", \"%s\")", $this->dsn, $this->username, $this->password);
        }        
    }
}