<?php
namespace Modules\Everdio\Library2 {
    class ECms extends \Modules\Database\Table {
        public function __construct(array $values = []) {
            $this->add("instance", new \Components\Validation(new \PDO("mysql:host=localhost", "evertdf", "geheim"), [new \Components\Validator\IsObject], \Components\Validation::NORMAL));
$this->add("database", new \Components\Validation("e_cms", [new \Components\Validator\IsString], \Components\Validation::NORMAL));
            $this->store($values);
        }
    }
}