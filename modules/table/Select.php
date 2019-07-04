<?php 
namespace Modules\Table {
    use \Components\Validator;
    final class Select extends \Components\Validation {
        public function __construct(array $mappers, array $select = []) {
            try {
                foreach ($mappers as $mapper) {               
                    if ($mapper instanceof \Modules\Table && isset($mapper->mapping)) {
                        foreach ($mapper->mapping as $parameter) {
                            $select[$parameter] = sprintf("%sAS`%s`", $mapper->getColumn($parameter), $parameter);
                        }
                    }
                }                
            } catch (\Components\Event $event) {
                throw new Event($event->getMessage());
            }
                        
            parent::__construct(implode(",", $select), [new Validator\IsString]);
        }
    }
}