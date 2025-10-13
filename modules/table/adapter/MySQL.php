<?php

namespace Modules\Table\Adapter {

    use Component\Validation,
        Component\Validator;

    final class MySQL extends \Modules\Table\Adapter {

        use \Modules\Table\MySQL;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "username" => new Validation(false, [new Validator\IsString]),
                "password" => new Validation(false, [new Validator\IsString]),
                "database" => new Validation(false, [new Validator\IsString]),
                    ] + $_parameters);            
        }

        public function models(array $models = []): array {
            $stm = $this->prepare(\sprintf("SHOW TABLES FROM %s", $this->database));
            $stm->execute();

            foreach ($stm->fetchAll(\PDO::FETCH_COLUMN) as $table) {

                $model = new \Modules\Table\Model\MySQL;
                $model->namespace = $this->namespace;
                $model->dsn = $this->dsn;
                $model->username = $this->username;
                $model->password = $this->password;
                $model->table = $table;
                $model->database = $this->database;
                $model->setup();
                
                $models[(string) $model] = $model;
            }
            
            return (array) $models;
        }
    }

}