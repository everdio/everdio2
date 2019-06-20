<?php
namespace Components\Validator {
    class IsDatetime extends \Components\Validator {
        const TYPE = "IS_DATETIME";
        const MESSAGE = "INVALID_DATETIME";
        
        protected $format = false;    
        
        public function __construct($format = "Y-m-d") {
            $this->format = $format;
        }
        
        public function getFormat() : string {
            return (string) $this->format;
        }
        
        public function execute($value) : bool {
            $datetime = \DateTime::createFromFormat($this->format, $value);
            return (bool) ($datetime && $datetime->format($this->format) == $value);
        }
        
        public function __dry() : string {
            return (string) sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->format));
        }        
    }
}