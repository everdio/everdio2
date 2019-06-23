<?php
namespace Modules\Node {
    use \Components\Validator;
    use \Modules\Node;
    class XPath extends \Components\Validation {
        public function __construct(Node $mapper, array $operators = [], $operator = "and", $expression = "=", array $cpath = []) {
            $xpath = (isset($mapper->current) ? $mapper->current : (isset($mapper->parent) ? sprintf("%s/%s", $mapper->parent, $mapper->tag) : $mapper->path));
            if ($mapper->hasMapping() || isset($mapper->Text)) {
                $operator = new XOperator($mapper, $operator, $expression);
                $xpath .= $operator->execute();
            }
            
            $event = new Event($xpath);

            parent::__construct($xpath, [new Validator\IsString\IsPath]);
        }
    }
}