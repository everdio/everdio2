
<?php
namespace Components\Adapter {   
    final class MongoDb {
        use \Components\Adapter;
        
        private $manager = false;
        
        public function __cosntruct(string $mongodb) {
            try {
                $this->manager = new \MongoDB\Driver\Manager($mongodb);
            } catch (Exception $ex) {
                throw new Event($ex->getMessage());
            }
        }
        
        public function execute() { 
        }
        
        public function __dry() : string {
            return (string) sprintf("new \Components\Resource\MongoDb(\"%s\", \"%s\", \"%s\")", $this->dsn, $this->username, $this->password);
        }        
    }
}