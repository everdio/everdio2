<?php
namespace Component\Caller {
    class DateFormatter extends \Component\Caller {
        public function __construct(string $format) {
            parent::__construct("datefmt_%s");
            $this->handle = $this->create(\Locale::getDefault(), 
                                          \IntlDateFormatter::NONE, 
                                          \IntlDateFormatter::NONE, 
                                          \date_default_timezone_get(), 
                                          \IntlDateFormatter::GREGORIAN, 
                                          $format);
        }
    }
}