<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Condition extends \Component\Validation {
        public function __construct(\Component\Core\Adapter\Mapper $mapper, string $operator = "and", string $expression = "=", string $match = "text() = \"%s\"", $wrap = "@%s %s \"%s\"", array $conditions = [], array $current = []) {
            if (isset($mapper->current) && ($parts = \explode(\DIRECTORY_SEPARATOR, $mapper->current)) && preg_match("/\[([^\]]*)\]/", end($parts), $current)) {
                $conditions[] = $current[1];
            } else {
                if ((isset($mapper->primary) && \sizeof(($parameters = $mapper->restore($mapper->primary)))) || (isset($mapper->mapping) && \sizeof(($parameters = $mapper->restore($mapper->mapping))))) {
                    foreach ($parameters as $parameter => $value) {
                        if (!$mapper->get($parameter)->has([Validator\IsEmpty::TYPE])) {
                            $conditions[] = \sprintf($wrap, $mapper->getField($parameter), $expression, $value);
                        }
                    }                    
                }

                if (isset($mapper->{$mapper->label})) {
                    $conditions[] = \sprintf($match, \trim($mapper->{$mapper->label}));
                }
            }

            parent::__construct(\implode(\sprintf(" %s ", \strtolower($operator)), $conditions), [new Validator\IsString]);
        }
    }
}