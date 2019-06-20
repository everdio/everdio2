<?php
namespace Components\Core\Parameter {
    use \Components\Validation;
    use \Components\Validator;    
    class Column extends \Components\Core\Parameter {
        public function __construct() {
            parent::__construct();
            $this->add("options", new Validation(false, array(new \Components\Validator\IsString)));
            $this->add("type", new Validation(false, array(new \Components\Validator\IsString)));
        }
        
        public function getValidators(array $validators = []) : array {
            if (isset($this->options)) {
                $options = (array) preg_split("/[(*)]/", str_replace("'", false, $this->options), false,  PREG_SPLIT_NO_EMPTY);
            }
            
            switch ($this->type) {
                case "int":
                case "integer":
                case "tinyint":
                case "bigint":                         
                    $options = array_slice($options, 0, 2);
                    $validators[] = new Validator\IsInteger;
                    $validators[] = new Validator\Len\Smaller(end($options));
                    break;
                case "text":
                case "char":
                case "varchar":
                    $validators[] = new Validator\IsString;
                    break;
                case "point":
                case "mediumtext":
                case "longtext":                          
                    $validators[] = new Validator\IsString;
                    break;            
                case "datetime":
                    $validators[] = new Validator\IsDatetime("Y-m-d H:i:s");
                    break;
                case "timestamp":
                    $validators[] = new Validator\IsDatetime\Timestamp("Y-m-d H:i:s");
                    break;
                case "date":
                    $validators[] = new Validator\IsDatetime\IsDate("Y-m-d");
                    break;
                case "enum":
                    $validators[] = new Validator\IsString\InArray(explode(",", end($options)));
                    unset($this->length);
                    break;
                case "set":
                    $validators[] = new Validator\IsArray\Intersect(explode(",", end($options)));
                    unset($this->length);
                    break;
                case "longblob":         
                    $validators[] = new Validator\IsBinary;
                    break;                        
                case "decimal":
                    $validators[] = new Validator\IsNumeric\Decimal;
                    break;
            }

            return (array) $validators;
        }
    }
}