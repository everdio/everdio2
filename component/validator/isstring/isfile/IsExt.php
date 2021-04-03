<?php
namespace Component\Validator\IsString\IsFile {
    class IsExt extends \Component\Validator\IsString\IsFile {
        protected $ext = 0;
        public function __construct(string $ext) {
            $this->ext = $ext;
        }
        
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && pathinfo($value, PATHINFO_EXTENSION) === $this->ext);
        }
    }
}