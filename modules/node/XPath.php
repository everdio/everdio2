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
            
            /*
            foreach ($operators as $operator) {
                if ($operator instanceof XOperator && $operator->mapper->path !== implode(DIRECTORY_SEPARATOR, $xpath)) {
                    if (isset($operator->mapper->current)) {
                        $xpath .= sprintf("[%s]", $operator->mapper->current);
                    } elseif (isset($operator->mapper->parent)) {
                        $xpath .= sprintf("[%s]", $operator->mapper->parent);
                    } elseif ($operator->mapper->hasMapping()) {
                        $xpath .= sprintf("[%s]", $operator->mapper->path . $operator->execute());
                    }
                }
            }    
             * 
             */


            parent::__construct($xpath, [new Validator\IsString\IsPath]);
        }
    }
}