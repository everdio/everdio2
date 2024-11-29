<?php

namespace Modules\Table\Adapter {

    final class SQLite extends \Modules\Table\Adapter {

        use \Modules\Table\SQLite;

        public function generate(array $parameters = []): void {
            $stm = $this->prepare(\sprintf("SHOW TABLES FROM %s", $this->database));
            $stm->execute();

            foreach ($stm->fetchAll(\PDO::FETCH_COLUMN) as $table) {
                $model = new \Modules\Table\Model\SQLite;
                $model->store($parameters);
                $model->label = $this->beautify($table);
                $model->class = $this->beautify($table);
                $model->table = $table;
                $model->resource = \sprintf("%s.%s", $this->database, $table);
                $model->setup();
            }
        }
    }

}