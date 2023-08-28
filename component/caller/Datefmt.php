<?php

namespace Component\Caller {

    class Datefmt extends \Component\Caller {

        public function __construct() {
            parent::__construct("datefmt_%s");
            $this->handle = $this->create(\Locale::getDefault(),
                    \IntlDateFormatter::NONE,
                    \IntlDateFormatter::NONE,
                    \date_default_timezone_get(),
                    \IntlDateFormatter::GREGORIAN);
        }
    }

}