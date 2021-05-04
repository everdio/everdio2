<?php
namespace Component\Validator\IsString\IsFile {
    class IsExtension extends \Component\Validator\IsString\IsFile {
        protected $extension = false;
        public function __construct(string $extension) {
            $this->extension = $extension;
        }
        
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && pathinfo($value, PATHINFO_EXTENSION) === $this->extension);
        }
    }
}