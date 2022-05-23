<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Count extends \Component\Validation {
        public function __construct(string $xpath, array $conditions = [], string $expression = "=", int $count = 1, $operator = "and", array $query = []) {
            foreach ($conditions as $condition) {
                if (($condition instanceof \Component\Validation && $condition->isValid())) {
                    $query[] = $condition->execute();
                }
            }
            
            parent::__construct((\sizeof($query) ? \sprintf("count(.%s[%s]) %s %s", $xpath, \implode(\sprintf(" %s ", $operator), $query), $expression, $count) : \sprintf("count(.%s) %s %s", $xpath, $expression, $count)), [new Validator\NotEmpty]);
        }
    }
}