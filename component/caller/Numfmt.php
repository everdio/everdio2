<?php
namespace Component\Caller {
    class Numfmt extends \Component\Caller {
        public function __construct(int $style = \NumberFormatter::CURRENCY) {
            parent::__construct("numfmt_%s");
            $this->handle = $this->create(\Locale::getDefault(), 
                                          $style);
        }
    }
}