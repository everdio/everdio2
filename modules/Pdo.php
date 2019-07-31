<?php
namespace Modules {   
    final class Pdo extends \PDO implements \Components\Core\Instance {
        use \Modules\Dsn;
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
    }
}