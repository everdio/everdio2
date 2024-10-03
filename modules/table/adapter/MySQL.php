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

        public function generate(array $parameters = []) {
            $stm = $this->prepare(\sprintf("SHOW TABLES FROM %s", "$this->database"));
            $stm->execute();
            
            foreach ($stm->fetchAll(\PDO::FETCH_COLUMN) as $table) {
                $model = new \Modules\Table\Model\MySQL;
                $model->store($parameters);
                $model->label = $this->beautify($table);
                $model->class = $this->beautify($table);
                $model->database = $this->database;
                $model->table = $table;
                $model->resource = \sprintf("`%s`.`%s`", $model->database, $model->table);
                $model->setup();
            }
        }
    }

}
