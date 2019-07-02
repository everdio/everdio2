<?php
namespace Modules\Table {
    class Operator extends \Components\Core\Operator { 
        public function execute(array $operators = []) : string {
            try {
                foreach ($this->mapper->restore($this->mapper->mapping) as $parameter => $value) {
                    if (!isset($this->mapper->invoke($parameter)->{"Components\Validator\IsEmpty"}) && !empty($value)) {                    
                        if (isset($this->mapper->invoke($parameter)->{"Components\Validator\IsInteger"}) || isset($this->mapper->{$parameter}->{"Components\Validator\IsNumeric"})) {
                            $operators[] =  sprintf("%s%s ", $this->mapper->getColumn($parameter) . $this->expression, $value);
                        } elseif (isset($this->mapper->invoke($parameter)->{"Components\Validator\IsString"}) || isset($this->mapper->invoke($parameter)->{"Components\Validator\IsString\InArray"})) {
                            $operators[] = sprintf("%s'%s'", $this->mapper->getColumn($parameter) . $this->expression, $value);
                        } elseif (isset($this->mapper->invoke($parameter)->{"Components\Validator\IsArray\Intersect"})) {                    
                            $operators[] = sprintf(" FIND_IN_SET('%s',%s)", implode(",", $value), $this->mapper->getColumn($parameter));
                        }            
                    }
                }                
            } catch (\Components\Event $event) {
                throw new Event($event->getMessage());
            }
            
            return (string) implode(strtoupper($this->operator), $operators);
        }
    }
}