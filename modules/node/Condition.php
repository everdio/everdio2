<?php

namespace Modules\Node {

    use \Component\Validator;

    final class Condition extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, string $operator = "and", string $expression = "=", array $key = [], array $conditions = []) {
            if (isset($mapper->index) && preg_match_all("/\[(\d+)\]/", $mapper->index, $key)) {
                parent::__construct($key[1][0], [new Validator\IsNumeric]);
            } else {
                if ((isset($mapper->primary) && \sizeof(($values = $mapper->restore($mapper->primary)))) || \sizeof(($values = $mapper->restore($mapper->mapping)))) {
                    foreach ($values as $parameter => $value) {
                        if (!empty($value) && $parameter !== $mapper->label) {
                            if (\is_numeric($value)) {
                                $conditions[$parameter] = \sprintf("@%s%s%s", $mapper->getField($parameter), $expression, $value);
                            } else {
                                $conditions[$parameter] = \sprintf("@%s%s\"%s\"", $mapper->getField($parameter), $expression, \html_entity_decode($value, \ENT_QUOTES | \ENT_HTML5, "UTF-8"));
                            }
                        }
                    }
                }

                if (isset($mapper->{$mapper->label}) && ($value = $mapper->{$mapper->label})) {
                    if (\is_numeric($value)) {
                        $conditions[$mapper->label] = \sprintf("number()%s%s", $expression, $value);
                    } else {
                        $conditions[$mapper->label] = \sprintf("text()%s\"%s\"", $expression, \html_entity_decode($value, \ENT_QUOTES | \ENT_HTML5, "UTF-8"));
                    }
                }

                if (\sizeof($conditions)) {
                    parent::__construct(\implode(\sprintf(" %s ", \strtolower($operator)), $conditions), [new Validator\IsString]);
                }
            }
        }
    }

}
