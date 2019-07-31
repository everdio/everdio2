<?php
namespace Modules\Table {
    use \Components\Validation;
    use \Components\Validator;    
    class Library extends \Components\Core\Mapping\Library {        
        public function __construct(\PDO $pdo, $database) {
            parent::__construct($pdo);
            $this->add("database", new Validation($database, [new Validator\IsString]));
            $this->extend = "\Modules\Table";
        }
        
        public function setup() {
            foreach ($this->execute(sprintf("SHOW TABLES FROM`%s`", $this->database))->fetchAll(\PDO::FETCH_COLUMN) as $table) {
                $model = new \Modules\Table\Model($this->instance);
                $model->database = $this->database;
                $model->table = $table;
                $model->root = $this->root;    
                $model->namespace = $this->getNamespace();
                $model->extend = $this->getExtend();
                $model->setup();
                
                $this->mappers = [sprintf("%s\%s", $model->namespace, $model->mapper)];
            }              
        }
        
        public function __destruct() {
            $this->remove("database");
            parent::__destruct();
        }
    }
}
