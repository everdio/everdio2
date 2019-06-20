<?php
namespace Components\Core\Mapper\Table {
    use Components\Validation;
    use Components\Validator;
    use COmponents\Core\Parameter\Column;
    class Model extends \Components\Core\Mapper\Model {
        public function __construct($resource) {
            parent::__construct($resource);
            $this->add("database", new Validation(false, array(new Validator\IsString)));
            $this->add("table", new Validation(false, array(new Validator\IsString)));                
            $this->add("keys", new Validation(false, array(new Validator\IsArray)));
        }
        
        public function setup() {
            $this->mapper = $this->labelize($this->table);
            foreach ($this->execute(sprintf("SELECT * FROM`information_schema`.`COLUMNS`WHERE`information_schema`.`COLUMNS`.`TABLE_SCHEMA`='%s'AND`information_schema`.`COLUMNS`.`TABLE_NAME`='%s'", $this->database, $this->table))->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $column = new Column;
                $column->parameter = $this->labelize($row["COLUMN_NAME"]);
                $column->field = $row["COLUMN_NAME"];
                $column->mandatory = (($row["IS_NULLABLE"] === "YES") ? false : true);
                $column->length = $row["CHARACTER_MAXIMUM_LENGTH"];
                $column->default = $this->hydrate(($row["COLUMN_DEFAULT"] === "CURRENT_TIMESTAMP" ? false : $row["COLUMN_DEFAULT"]));
                $column->type = $row["DATA_TYPE"];
                $column->options = $row["COLUMN_TYPE"];                
                
                $this->add($column->parameter, $column->getValidation($column->getValidators()));
                $this->mapping = [$column->field => $column->parameter];
            }
            
            foreach ($this->execute(sprintf("SELECT * FROM`information_schema`.`KEY_COLUMN_USAGE`WHERE`information_schema`.`KEY_COLUMN_USAGE`.`CONSTRAINT_NAME`='PRIMARY'AND`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_SCHEMA`='%s'AND`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_NAME`='%s'", $this->database, $this->table))->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $this->keys = [$this->getParameter($row["COLUMN_NAME"])];
            }
            
            foreach($this->execute(sprintf("SELECT * FROM`information_schema`.`KEY_COLUMN_USAGE`WHERE`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_SCHEMA`='%s'AND`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_NAME`='%s'", $this->database, $this->table))->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                if ($row["REFERENCED_COLUMN_NAME"]) {
                    $this->relations = array($this->getParameter($row["COLUMN_NAME"]) => $this->namespace . "\\" . $this->labelize($row["REFERENCED_TABLE_NAME"]));    
                }
            }
        }
    }
}