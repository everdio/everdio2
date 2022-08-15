<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Condition extends \Component\Validation {
        public function __construct(\Component\Core\Adapter\Mapper $mapper, string $operator = "and", string $expression = "=", string $match = "text()", array $conditions = []) {
            if ((isset($mapper->primary) && \sizeof(($values = $mapper->restore($mapper->primary)))) || (isset($mapper->mapping) && \sizeof(($values = $mapper->restore($mapper->mapping))))) {
                foreach ($values as $parameter => $value) {
                    if (!empty($value)) {
                        $conditions[$parameter] = \sprintf((\is_numeric($value) ? "@%s%s%s" : "@%s%s'%s'"), $mapper->getField($parameter), $expression, $value);
                    }
                }                
            }

            if (isset($mapper->{$mapper->label})) {
                $conditions[$mapper->label] = \sprintf((\is_numeric($mapper->{$mapper->label}) ? "%s%s%s" : "%s%s'%s'"), $match, $expression, \trim($mapper->{$mapper->label}));
            }            
            
            if (\sizeof($conditions)) {            
                parent::__construct(\implode(\sprintf(" %s ", \strtolower($operator)), $conditions), [new Validator\IsString]);
            }
        }
    }
}