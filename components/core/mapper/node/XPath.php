<?php
namespace Components\Core\Mapper\Node {
    use \Components\Validator;
    use \Components\Core\Mapper\Node;
    class XPath extends \Components\Validation {
        public function __construct(Node $mapper, array $operators = [], $operator = "and", $expression = "=", array $cpath = [], array $xpath = []) {
            $operators[] = new XOperator($mapper, $operator, $expression);
            foreach (explode(DIRECTORY_SEPARATOR, $mapper->path) as $key => $path) {
                $cpath[$key] = $path;
                $xpath[$key] = $path;
                foreach ($operators as $operator) {
                    if ($operator instanceof XOperator && $operator->mapper->path === implode(DIRECTORY_SEPARATOR, $cpath) && ($operator->mapper->hasMapping() || isset($operator->mapper->Text))) {
                        $xpath[$key] = $operator->mapper->tag . $operator->execute();
                    }
                }                    
            }

            foreach ($operators as $operator) {
                if ($operator instanceof XOperator && $operator->mapper->hasMapping() || isset($operator->mapper->Text)) {
                    if (!$mapper->isParent($operator->mapper) && $mapper->isChild($operator->mapper)) {
                        $xpath[$key] .= sprintf("[.%s]", str_replace($mapper->path, false, $operator->mapper->path) . $operator->execute());
                    } elseif (!$mapper->isParent($operator->mapper) && !$mapper->isChild($operator->mapper)) {
                        $xpath[$key] .= sprintf("[%s]", $operator->mapper->path . $operator->execute());
                    }
                }
            }        
       
      
 
            parent::__construct(implode(DIRECTORY_SEPARATOR, $xpath), [new Validator\IsString\IsPath]);
        }
    }
}