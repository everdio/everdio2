<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Condition extends \Component\Validation {
        public function __construct(\Component\Core\Adapter\Mapper $mapper, string $operator = "and", string $expression = "=", string $match = "text()", array $conditions = [], array $current = []) {
            if (isset($mapper->current) && ($parts = \explode(\DIRECTORY_SEPARATOR, $mapper->current)) && preg_match("/\[([^\]]*)\]/", end($parts), $current)) {
                $conditions[] = $current[1];
            } else {
                if ((isset($mapper->primary) && \sizeof(($values = $mapper->restore($mapper->primary)))) || (isset($mapper->mapping) && \sizeof(($values = $mapper->restore($mapper->mapping))))) {
                    foreach ($values as $parameter => $value) {
                        if (!empty($value)) {
                            $conditions[$parameter] = \sprintf((isset($mapper->get($parameter)->IS_NUMERIC) ? "@%s%s%s" : "@%s%s'%s'"), $mapper->getField($parameter), $expression, $value);
                        }
                    }                
                }
                
                if (isset($mapper->{$mapper->label})) {
                    $conditions[$mapper->label] = \sprintf((isset($mapper->get($mapper->label)->IS_NUMERIC) ? "%s%s%s" : "%s%s'%s'"), $match, $expression, \trim($mapper->{$mapper->label}));
                }
            }
            
            parent::__construct(\implode(\sprintf(" %s ", \strtolower($operator)), $conditions), [new Validator\IsString]);
        }
    }
}