<?php
namespace Modules\Node {
    use \Components\Validation;
    use \Components\Validator;
    final class XOperator extends \Components\Core\Operator {
        public function __construct(\Modules\Node $mapper, string $operator = "and", string $expression = "=", string $match = "contains") {
            parent::__construct($operator, $expression);            
            $this->add("path", new Validation($mapper->path, [new Validator\IsString\IsPath]));

            if ($mapper->hasMapping() || isset($mapper->Text)) {
                if ($mapper->hasMapping()) {
                    foreach ($mapper->restore($mapper->mapping) as $parameter => $value) {
                        if (!empty($value)) {
                            $this->operators = [sprintf("@%s%s\"%s\"", $mapper->getField($parameter), $this->expression, $value)];
                        }
                    }                    
                }

                if (isset($mapper->Text)) {
                    $this->operators = [sprintf("%s(.,\"%s\")", $match, trim($mapper->Text))];
                }   
            }
        }
        
        public function execute() : string {
            return (string) sprintf("[%s]", implode(sprintf(" %s ", strtolower($this->operator)), $this->operators));
        }
    }
}