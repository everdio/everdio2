<?php
namespace Modules\Node {
    use \Components\Validator;
    final class Filter extends \Components\Validation {
        public function __construct(string $xpath, array $conditions = [], string $operator = "and", array $filters = []) {
            foreach ($conditions as $condition) {
                if ($condition instanceof Condition && $condition->isValid()) {
                    $filters[] = $condition->execute();
                }
            }

            //parent::__construct(sprintf("%s[%s]", $node->path, implode(sprintf(" %s ", $operator), $filters)), [new Validator\IsString\Contains(["@", "contains", "text", "=", ">", "<", "!=", "and", "or", "min", "max", "last", "first"])]);
            parent::__construct((sizeof($filters) ? sprintf("%s[%s]", $xpath, implode(sprintf(" %s ", $operator), $filters)) : $xpath), [new Validator\IsString\Contains(["@", "contains", "text", "=", ">", "<", "!=", "and", "or", "min", "max", "last", "first"])]);
        }
    }
}