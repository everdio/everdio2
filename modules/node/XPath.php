<?php
namespace Modules\Node {
    use \Components\Validator;
    use \Modules\Node;
    class XPath extends \Components\Validation {
        public function __construct(Node $mapper, array $operators = [], $operator = "and", $expression = "=") {
            $xpath = (isset($mapper->current) ? $mapper->current : (isset($mapper->parent) ? sprintf("%s/%s", $mapper->parent, $mapper->tag) : $mapper->path));
            
            if ($mapper->hasMapping() || isset($mapper->Text)) {
                $operator = new XOperator($mapper, $operator, $expression);
                $xpath .= $operator->execute();
            }
            
            foreach ($operators as $operator) {
                if ($operator instanceof XOperator && ($operator->mapper->hasMapping() || isset($operator->mapper->Text))) {
                    if (isset($operator->mapper->current)) {
                        $xpath .= sprintf("[.%s]", $operator->mapper->current);
                    } elseif (isset($operator->mapper->parent)) {
                        $xpath .= sprintf("[.%s/%s]", $operator->mapper->parent, $operator->mapper->tag . $operator->execute());
                    } else {
                        $xpath .= sprintf("[.%s%]", $operator->mapper->path . $operator->execute());
                    }
                }
            }    
            
            parent::__construct($xpath, [new Validator\IsString\IsPath]);
        }
        
        private function isParent(Node $mapper) : bool {
            return (bool) (implode(DIRECTORY_SEPARATOR, array_intersect_assoc(explode(DIRECTORY_SEPARATOR, $mapper->path), explode(DIRECTORY_SEPARATOR, $this->path))) === $mapper->path);
        }
        
        private function isChild(Node $mapper) : bool {
            return (bool) (implode(DIRECTORY_SEPARATOR, array_intersect_assoc(explode(DIRECTORY_SEPARATOR, $this->path), explode(DIRECTORY_SEPARATOR, $mapper->path))) === $this->path);            
        }        
    }
}