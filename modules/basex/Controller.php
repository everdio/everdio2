<?php

namespace Modules\BaseX {

    use \Component\Validation,
        \Component\Validator,
        \Component\Core\Parameters,
        \Modules\Node;

    class Controller extends \Component\Core\Controller\Model\Http {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "basex" => new Validation(new Parameters, [new Validator\IsObject]),
                    ] + $_parameters);
        }

        final protected function filters(array $parameters = [], array $validations = [], array $mappers = []): array {
            foreach ($parameters as $parameter => $values) {
                if (isset($this->basex->{$parameter})) {
                    foreach ((array) $values as $value) {
                        $mapper = new $this->basex->{$parameter}->mapper([$this->basex->{$parameter}->parameter => $value]);
                        $mappers[$mapper->path][$this->basex->{$parameter}->operator][] = new Node\Condition($mapper, $this->basex->{$parameter}->operator, $this->basex->{$parameter}->expression);
                    }
                }
            }

            \ksort($mappers);

            foreach ($mappers as $path => $operators) {
                foreach ($operators as $operator => $conditions) {
                    $validations[$path] = new Node\Filter($path, $conditions, $operator);
                }
            }

            \ksort($validations);

            return (array) $validations;
        }

        final protected function fetch(string $parameter, array $parameters = [], string $wrap = "(%s)"): string|int {
            return (new $this->basex->{$parameter}->mapper)->fetch($this->basex->{$parameter}->parameter, $this->filters($parameters), $wrap);
        }
        
        final protected function fetchAll(string $parameter, array $parameters = [], string $wrap = "distinct-values(%s)"): array {
            $results = (array) (new $this->basex->{$parameter}->mapper)->fetchAll($this->basex->{$parameter}->parameter, $this->filters($parameters), $wrap);
            \sort($results);

            return (array) $results;
        }        

        final protected function sum(string $parameter, array $parameters = [], string $wrap = "sum(%s)"): string|int {
            return (new $this->basex->{$parameter}->mapper)->fetch($this->basex->{$parameter}->parameter, $this->filters($parameters), $wrap);
        }

        final protected function count(string $parameter, array $parameters = []): int {
            return (int) (new $this->basex->{$parameter}->mapper)->count($this->filters($parameters));
        }

        final protected function find(string $parameter, array $parameters = [], string $query = "[1]"): \Component\Core\Adapter\Mapper {
            return (object) (new $this->basex->{$parameter}->mapper)->find($this->filters($parameters), $query);
        }

        final protected function findAll(string $parameter, array $parameters = []): array {
            return (array) (new $this->basex->{$parameter}->mapper)->findAll($this->filters($parameters));
        }
    }

}
