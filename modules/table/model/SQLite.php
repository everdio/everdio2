<?php

namespace Modules\Table\Model {

    use Component\Validation,
        Component\Validator;

    final class SQLite extends \Modules\Table\Model {

        use \Modules\Table\SQLite;

        public function __construct(string $table, array $_parameters = []) {
            parent::__construct([
                "table" => new Validation($table, array(new Validator\IsString))
                    ] + $_parameters);
            
            foreach (\array_keys($_parameters) as $parameter) {
                $this->mapping = [$parameter => $parameter];
            }
        }

        final public function setup(): void {
            $this->label = $this->beautify($this->table);
            $this->class = $this->beautify($this->table);
            $this->resource = \sprintf("`%s`", $this->table);
        }

        final public function create(array $columns = []): void {
            
            foreach ($this->mapping as $field => $parameter) {
                $validation = $this->getParameter($parameter);

                $length = ($validation->has(["length"]) ? \sprintf(" (%s)", $validation->getLen()) : false);

                if ($validation->has(["string"])) {
                    $columns[] = \sprintf("%s VARCHAR %s", $field, $length);
                }
            }
            
            $this->exec(\sprintf("CREATE TABLE IF NOT EXISTS %s (%s)", $this->table, \implode(", ", $columns)));
        }
    }

}