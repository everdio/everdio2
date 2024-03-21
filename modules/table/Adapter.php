<?php

namespace Modules\Table {

    use Component\Validation,
        Component\Validator;

    class Adapter extends \Component\Core\Adapter {

        use \Modules\Table\Pdo;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "adapter" => new Validation(false, [new Validator\IsArray]),
                "dsn" => new Validation(false, [new Validator\IsString]),
                "username" => new Validation(false, [new Validator\IsString]),
                "password" => new Validation(false, [new Validator\IsString]),
                "database" => new Validation(false, [new Validator\IsString]),
                    ] + $_parameters);
        }

        public function generate(string $model, array $parameters = []) {
            $stm = $this->prepare(\sprintf("SHOW TABLES FROM`%s`", $this->database));
            $stm->execute();
            foreach ($stm->fetchAll(\PDO::FETCH_COLUMN) as $table) {
                $mapper = new $model;
                $mapper->store($parameters);
                $mapper->label = $this->beautify($table);
                $mapper->class = $this->beautify($table);
                $mapper->table = $table;
                $mapper->setup();
            }
        }
    }

}
