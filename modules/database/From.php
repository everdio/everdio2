<?php 
namespace Modules\Database {
    use \Components\Validator;
    final class From extends \Components\Validation {
        public function __construct(array $mappers, array $from = []) {
            try {
                foreach ($mappers as $mapper) {               
                    if ($mapper instanceof Table) {
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