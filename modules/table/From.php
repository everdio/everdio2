<?php 
namespace Modules\Table {
    use \Components\Validator;
    final class From extends \Components\Validation {
        public function __construct(array $mappers, array $from = []) {
            try {
                foreach ($mappers as $as => $mapper) {               
                    if ($mapper instanceof \Modules\Table) {
                        $from[] = $mapper->getTable();
                    }
                }                
            } catch (\Components\Event $event) {
                throw new Event($event->getMessage());
            }
                        
            parent::__construct(implode(",", $from), [new Validator\IsString]);
        }
    }
}