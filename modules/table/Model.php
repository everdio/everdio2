<?php
namespace Modules\Table {
    use Components\Validation;
    use Components\Validator;
    final class Model extends \Components\Core\Adapter\Mapper\Model {
        public function __construct($key) {
            parent::__construct($key);
            $this->extends = "\Modules\Table";
            $this->add("instance", new Validation(false, [new Validator\IsObject]));
            $this->add("database", new Validation(false, array(new Validator\IsString)));
            $this->add("table", new Validation(false, array(new Validator\IsString)));                
            $this->add("keys", new Validation(false, array(new Validator\IsArray)));
            $this->add("relations", new Validation(false, array(new Validator\IsArray)));
        }
        
        public function setup() {
            $columns = $this->prepare(sprintf("SELECT * FROM`information_schema`.`COLUMNS`WHERE`information_schema`.`COLUMNS`.`TABLE_SCHEMA`='%s'AND`information_schema`.`COLUMNS`.`TABLE_NAME`='%s'", $this->database, $this->table));
            $columns->execute();

            foreach ($columns->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $column = new Column($row["COLUMN_NAME"]);
                $column->mandatory = (($row["IS_NULLABLE"] === "YES") ? false : true);
                $column->length = $row["CHARACTER_MAXIMUM_LENGTH"];
                $column->default = $this->hydrate(($row["COLUMN_DEFAULT"] === "CURRENT_TIMESTAMP" ? false : $row["COLUMN_DEFAULT"]));
                $column->type = $row["DATA_TYPE"];
                $column->options = $row["COLUMN_TYPE"];

                $sample = $this->prepare(sprintf("SELECT `%s` FROM `%s`.`%s`", $row["COLUMN_NAME"], $this->database, $this->table));
                if ($sample && $sample->execute()) {
                    $column->sample = $sample->fetchColumn();
                }                                        

                $this->add($column->parameter, $column->getValidation($column->getValidators()));
                $this->mapping = [$row["COLUMN_NAME"] => $column->parameter];
            }
                
            $keys = $this->prepare(sprintf("SELECT * FROM`information_schema`.`KEY_COLUMN_USAGE`WHERE`information_schema`.`KEY_COLUMN_USAGE`.`CONSTRAINT_NAME`='PRIMARY'AND`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_SCHEMA`='%s'AND`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_NAME`='%s'", $this->database, $this->table));            $keys->execute();
            
            foreach ($keys->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $this->keys = [$row["COLUMN_NAME"] => $this->mapping[$row["COLUMN_NAME"]]];
            }
            
            $foreign = $this->prepare(sprintf("SELECT * FROM`information_schema`.`KEY_COLUMN_USAGE`WHERE`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_SCHEMA`='%s'AND`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_NAME`='%s'", $this->database, $this->table));
            $foreign->execute();
            
            foreach($foreign->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                if ($row["REFERENCED_COLUMN_NAME"]) {
                    $this->relations = array($this->mapping[$row["COLUMN_NAME"]] => $this->namespace . "\\" . $this->labelize($row["REFERENCED_TABLE_NAME"]));    
                }
            }
            
            $relations = $this->prepare(sprintf("SELECT * FROM`information_schema`.`KEY_COLUMN_USAGE`WHERE`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_SCHEMA`='%s'AND`information_schema`.`KEY_COLUMN_USAGE`.`REFERENCED_TABLE_NAME`='%s'", $this->database, $this->table));
            $relations->execute();            

            foreach($relations->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $this->relations = array($this->mapping[$row["COLUMN_NAME"]] => $this->namespace . "\\" . $this->labelize($row["TABLE_NAME"]));    
            }            
        }
    }
}