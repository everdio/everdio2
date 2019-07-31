<?php
namespace Modules {
    use \Components\Validation;
    use \Components\Validator;     
    class Database extends \Components\Core\Adapter\Mapper\Model {
        public function __construct(\Modules\Constructor\Dsn $constructor) {
            parent::__construct($constructor);
            $this->model = __DIR__ . DIRECTORY_SEPARATOR . "Database.tpl";
            $this->add("database", new Validation(false, [new Validator\IsString]));
        }
        
        public function setup() {
            $stm = $this->prepare(sprintf("SHOW TABLES FROM`e_cms`"));
            $stm->execute();

            foreach ($stm->fetchAll(\PDO::FETCH_COLUMN) as $table) {
                $model = new \Modules\Database\Model($this->instance);
                $model->database = $this->database;
                $model->table = $table;
                $model->class = $this->labelize($table);
                $model->path = $this->path;
                $model->extends = $this->namespace . "\\" . $this->class; 
                $model->namespace = $model->extends;
                $model->setup();
            }
            
            $this->remove("mapping");
        }
    }
}
