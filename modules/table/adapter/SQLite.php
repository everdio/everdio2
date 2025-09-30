<?php

namespace Modules\Table\Adapter {

    final class SQLite extends \Modules\Table\Adapter {

        use \Modules\Table\SQLite;

        final public function models(array $tables = [], array $models = []): array {
            $path = \strtolower(\implode(\DIRECTORY_SEPARATOR, \explode("\\", $this->namespace)));
            $dsn = \sprintf("sqlite:%s/%s.db", \dirname((new \Component\Path(\dirname($path)))->getRealPath()), \basename($path));

            foreach ($tables as $mapper => $table) {
                $parameters = [];

                if (\array_key_exists("mapping", $table)) {
                    foreach ($table["mapping"] as $parameter) {
                        if (\array_key_exists($parameter, $table)) {
                            $parameters[$parameter] = (new \Component\Validation\Parameter($table[$parameter]["value"], $table[$parameter]["default"], $table[$parameter]["mandatory"], $table[$parameter]["length"], \explode(",", $table[$parameter]["options"])))->getValidation();
                        }
                    }

                    $model = new \Modules\Table\Model\SQLite($parameters);
                    $model->dsn = $dsn;
                    $model->namespace = $this->namespace;
                    $model->table = $table["table"];

                    if (\array_key_exists("primary", $table)) {
                        $model->primary = $table["primary"];
                    }

                    if (\array_key_exists("keys", $table)) {
                        $model->keys = $table["keys"];
                    }

                    $model->mapping = $table["mapping"];

                    if (\array_key_exists("parents", $table)) {
                        foreach ($table["parents"] as $key => $parent) {
                            if (\array_key_exists($parent, $models)) {
                                $model->parents = [$key => (string) $models[$parent]];
                            }
                        }
                    }

                    $model->setup($models);
                    $models[$mapper] = $model;
                }
            }

            return (array) $models;
        }
    }

}