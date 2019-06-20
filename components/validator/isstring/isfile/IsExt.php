<?php
namespace Components\Validator\IsString\IsFile {
    class IsExt extends \Components\Validator\IsString\IsFile {
        protected $ext = 0;
        public function __construct(string $ext) {
            $this->ext = $ext;
        }
        
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && pathinfo($value, PATHINFO_EXTENSION) === $this->ext);
        }
    }
}