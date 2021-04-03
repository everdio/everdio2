<?php
namespace Component\Validator\IsString {
    class IsDatetime extends \Component\Validator\IsString {
        const MESSAGE = "INVALID_DATETIME";
        const TYPE = "IS_DATETIME";
        protected $format = false;    
        
        public function __construct($format = "Y-m-d H:i:s") {
            $this->format = $format;
        }
        
        public function getFormat() : string {
            return (string) $this->format;
        }
        
        public function execute($value) : bool {
            if (parent::execute($value)) {
                $datetime = \DateTime::createFromFormat($this->format, $value);
                return (bool) ($datetime && $datetime->format($this->format) == $value);
            }
            
            return (bool) false;
        }
        
        public function __dry() : string {
            return (string) sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->format));
        }        
    }
}