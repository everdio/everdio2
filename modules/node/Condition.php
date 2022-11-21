<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Condition extends \Component\Validation {
        public function __construct(\Component\Core\Adapter\Mapper $mapper, string $operator = "and", string $expression = "=", string $match = "text()", array $key = [], array $conditions = []) {            
            if (isset($mapper->index) && preg_match_all("/\[(\d+)\]/", $mapper->index, $key)) {
                parent::__construct($key[1][0], [new Validator\IsNumeric]);
            } else {
                if ((isset($mapper->primary) && \sizeof(($values = $mapper->restore($mapper->primary)))) || (isset($mapper->mapping) && \sizeof(($values = $mapper->restore($mapper->mapping))))) {
                    foreach ($values as $parameter => $value) {
                        if (!empty($value)) {
                            $conditions[$parameter] = $this->getCondition($mapper->get($parameter), $mapper->getField($parameter), $expression, $value);
                        }                       
                    }                
                }

                if (isset($mapper->{$mapper->label})) {
                    $conditions[$mapper->label] = \sprintf((\is_numeric($mapper->{$mapper->label}) ? "%s %s %s" : "%s %s '%s'"), $match, $expression, \trim($mapper->{$mapper->label}));
                }            

                if (\sizeof($conditions)) {            
                    parent::__construct(\implode(\sprintf(" %s ", \strtolower($operator)), $conditions), [new Validator\IsString]);
                }
            }
        }
        
        private function getCondition(\Component\Validation $validation, string $field, string $expression, $value) : string {
            if(isset($validation->IS_NUMERIC)) {
                return (string) \sprintf("@%s%s%s", $field, $expression, $value);
            } else {
                return (string) \sprintf("@%s%s'%s'", $field, $expression, $value);
            }            
        }
    }
}
