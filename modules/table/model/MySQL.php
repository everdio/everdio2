<?php

namespace Modules\Table\Model {

    use Component\Validation,
        Component\Validator;

    final class MySQL extends \Modules\Table\Model {

        use \Modules\Table\MySQL;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "username" => new Validation(false, array(new Validator\IsString)),
                "password" => new Validation(false, array(new Validator\IsString)),
                "database" => new Validation(false, array(new Validator\IsString)),
                "table" => new Validation(false, array(new Validator\IsString))
                    ] + $_parameters);            
        }

        final public function setup(): void {
            $columns = $this->prepare(\sprintf("SELECT * FROM`information_schema`.`COLUMNS`WHERE`information_schema`.`COLUMNS`.`TABLE_SCHEMA`='%s'AND`information_schema`.`COLUMNS`.`TABLE_NAME`='%s' ORDER BY `ORDINAL_POSITION` ASC", $this->database, $this->table));
            $columns->execute();
            foreach ($columns->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $sample = $this->prepare(\sprintf("SELECT`%s`FROM%sWHERE`%s`IS NOT NULL ORDER BY CHARACTER_LENGTH(`%s`)DESC LIMIT 1", $row["COLUMN_NAME"], $this->resource, $row["COLUMN_NAME"], $row["COLUMN_NAME"]));
                $value = (empty($row["COLUMN_DEFAULT"]) && ($sample && $sample->execute()) ? $this->desanitize($sample->fetchColumn()) : \str_replace("'", "", $row["COLUMN_DEFAULT"]));
                switch ($row["DATA_TYPE"]) {
                    case "char":
                    case "longtext":
                    case "mediumtext":
                    case "tinytext":
                    case "text":
                    case "varchar":
                    case "bigint":
                    case "tinyint":
                    case "mediumint":
                    case "smallint":
                    case "decimal":
                    case "int":
                    case "point":
                    case "varbinary":
                    case "float":
                        $parameter = new Validation\Parameter($this->hydrate($value), !empty($row["COLUMN_DEFAULT"]), ($row["IS_NULLABLE"] === "YES" ? false : true), $row["CHARACTER_MAXIMUM_LENGTH"]);
                        break;
                    case "enum":
                        $parameter = new Validation\Parameter($this->hydrate($value), !empty($row["COLUMN_DEFAULT"]), ($row["IS_NULLABLE"] === "YES" ? false : true), NULL, \explode(",", \str_replace("'", "", trim($row["COLUMN_TYPE"], "enum()"))));
                        break;
                    case "set":
                        $parameter = new Validation\Parameter(\explode(",", $this->hydrate($value)), !empty($row["COLUMN_DEFAULT"]), ($row["IS_NULLABLE"] === "YES" ? false : true), NULL, \explode(",", \str_replace("'", "", \trim($row["COLUMN_TYPE"], "set()"))));
                        break;
                    case "date":
                        $parameter = new Validation\Parameter(\date("Y-m-d"), false, ($row["IS_NULLABLE"] === "YES" ? false : true));
                        break;
                    case "datetime":
                    case "timestamp":
                        $parameter = new Validation\Parameter(\date("Y-m-d H:i:s"), false, ($row["IS_NULLABLE"] === "YES" ? false : true));
                        break;
                    default:
                        throw new \LogicException(\sprintf("unknown column type: %s (`%s`.`%s`)", $row["DATA_TYPE"], $this->resource, $row["COLUMN_NAME"]));
                }

                $this->addParameter($this->beautify($row["COLUMN_NAME"]), $parameter->getValidation($parameter->getValidators()));
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