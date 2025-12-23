<?php

namespace Modules\Table\Model {

    use Component\Validation,
        Component\Validator;

    final class Mysql extends \Modules\Table\Model {

        use \Modules\Table\Mysql;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "username" => new Validation(false, array(new Validator\IsString)),
                "password" => new Validation(false, array(new Validator\IsString)),
                "database" => new Validation(false, array(new Validator\IsString))
                    ] + $_parameters);

            $this->adapter = ["dsn", "username", "database"];
            $this->use = "\Modules\Table\MySQL";
        }

        final public function setup(): void {
            $this->label = $this->beautify($this->table);
            $this->class = $this->beautify($this->table);
            $this->resource = \sprintf("`%s`.`%s`", $this->database, $this->table);

            $columns = $this->prepare(\sprintf("SELECT * FROM`information_schema`.`COLUMNS`WHERE`information_schema`.`COLUMNS`.`TABLE_SCHEMA`='%s'AND`information_schema`.`COLUMNS`.`TABLE_NAME`='%s' ORDER BY `ORDINAL_POSITION` ASC", $this->database, $this->table));
            $columns->execute();

            foreach ($columns->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $validators = [];
                $options = [];
                $default = \str_replace("'", "", (string) $row["COLUMN_DEFAULT"]);

                switch ($row["DATA_TYPE"]) {
                    case "date":
                        $values = [\date("Y-m-d")];
                        break;
                    case "datetime":
                    case "timestamp":
                        $values = [\date("Y-m-d H:i:s")];
                        break;
                    case "decimal":
                        $values = [123.45];
                        break;
                    case "char":
                    case "longtext":
                    case "mediumtext":
                    case "tinytext":
                    case "text":
                    case "varchar":
                        $values = ["test"];
                        break;
                    case "int":
                    case "bigint":
                    case "tinyint":
                    case "mediumint":
                    case "smallint":
                        $values = [123];
                        break;
                    case "enum":
                        $values = $options = \explode(",", \str_replace("'", "", \trim($row["COLUMN_TYPE"], "enum()")));
                        break;
                    case "set":
                        $values = $options = \explode(",", \str_replace("'", "", \trim($row["COLUMN_TYPE"], "set()")));
                        break;
                    default:
                        $sample = $this->prepare(\sprintf("SELECT`%s`FROM%sWHERE`%s`IS NOT NULL ORDER BY CHARACTER_LENGTH(`%s`)DESC LIMIT 5", $row["COLUMN_NAME"], $this->resource, $row["COLUMN_NAME"], $row["COLUMN_NAME"]));
                        $sample->execute();
                        $values = $sample->fetchAll(\PDO::FETCH_COLUMN);
                        break;
                }
                
                foreach ($values as $value) {
                    $validators = \array_unique(\array_merge($validators, (new Validation\Parameter($value))->getValidators()));
                }
   
                $this->addParameter($this->beautify($row["COLUMN_NAME"]), (new Validation\Parameter($default, !empty($row["COLUMN_DEFAULT"]), ($row["IS_NULLABLE"] === "YES" ? false : true), $row["CHARACTER_MAXIMUM_LENGTH"], $options))->getValidation($validators));
                $this->mapping = [$row["COLUMN_NAME"] => $this->beautify($row["COLUMN_NAME"])];
            }

            $keys = $this->prepare(\sprintf("SELECT * FROM`information_schema`.`KEY_COLUMN_USAGE`WHERE`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_SCHEMA`='%s'AND`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_NAME`='%s'", $this->database, $this->table));
            $keys->execute();
            foreach ($keys->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                if ($row["CONSTRAINT_NAME"] == "PRIMARY") {
                    $this->primary = [$row["COLUMN_NAME"] => $this->mapping[$row["COLUMN_NAME"]]];
                } elseif ($row["REFERENCED_COLUMN_NAME"]) {
                    $this->keys = [$this->mapping[$row["COLUMN_NAME"]] => $this->beautify($row["REFERENCED_COLUMN_NAME"])];
                }
            }

            $foreign = $this->prepare(\sprintf("SELECT * FROM`information_schema`.`KEY_COLUMN_USAGE`WHERE`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_SCHEMA`='%s'AND`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_NAME`='%s'", $this->database, $this->table));
            $foreign->execute();
            foreach ($foreign->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                if ($row["REFERENCED_COLUMN_NAME"]) {
                    $this->parents = [$this->mapping[$row["COLUMN_NAME"]] => $this->namespace . "\\" . $this->beautify($row["REFERENCED_TABLE_NAME"])];
                }
            }

            /*
              $many = $this->prepare(\sprintf("SELECT * FROM`information_schema`.`KEY_COLUMN_USAGE`WHERE`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_SCHEMA`='%s'AND`information_schema`.`KEY_COLUMN_USAGE`.`REFERENCED_TABLE_NAME`='%s'AND`information_schema`.`KEY_COLUMN_USAGE`.`TABLE_NAME`!='%s'", $this->resource, $this->table));
              $many->execute();
              foreach ($many->fetchAll(\PDO::FETCH_ASSOC) as $row) {
              $this->parents = [$this->beautify($row["COLUMN_NAME"]) => $this->namespace . "\\" . $this->beautify($row["TABLE_NAME"])];
              }
             * 
             */
        }
    }

}