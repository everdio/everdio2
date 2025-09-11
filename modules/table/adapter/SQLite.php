<?php

namespace Modules\Table\Adapter {

    final class SQLite extends \Modules\Table\Adapter {

        use \Modules\Table\SQLite;
        
        public function __construct(array $_parameters = []) {
            parent::__construct([
                "model" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                    ] + $_parameters);
        }        

        public function models(array $models = []): array {
            $stm = $this->prepare(\sprintf("SHOW TABLES FROM %s", $this->database));
            $stm->execute();

            foreach ($stm->fetchAll(\PDO::FETCH_COLUMN) as $table) {
                $model = new \Modules\Table\Model\SQLite;
                $model->namepspace = $this->namespace;
                $model->table = $table; 
                $model->setup();
                
                $models[(string) $model] = $model;
            }
            
            return (array) $models;
        }
    }

}