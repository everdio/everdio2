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
                $model->table = $table; 
                $model->setup();
            }
        }
    }

}