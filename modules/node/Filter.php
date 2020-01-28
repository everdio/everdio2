<?php
namespace Modules\Node {
    use \Components\Validator;
    final class Filter extends \Components\Validation {
        public function __construct(\Modules\Node $node, array $conditions = [], string $operator = "and", array $filters = []) {
            foreach ($conditions as $condition) {
                if ($condition instanceof Condition && $condition->isValid()) {
                    $filters[] = $condition->execute();
                }
            }

            //parent::__construct(sprintf("%s[%s]", $node->path, implode(sprintf(" %s ", $operator), $filters)), [new Validator\IsString\Contains(["@", "contains", "text", "=", ">", "<", "!=", "and", "or", "min", "max", "last", "first"])]);
            parent::__construct((sizeof($filters) ? sprintf("%s[%s]", $node->path, implode(sprintf(" %s ", $operator), $filters)) : $node->path), [new Validator\IsString\Contains(["@", "contains", "text", "=", ">", "<", "!=", "and", "or", "min", "max", "last", "first"])]);
        }
    }
}