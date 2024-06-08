<?php

/*
 * overriding Modules\Node query() and evaluate()
 */

namespace Modules\BaseX {

    use \Modules\Node;

    trait Api {

        use \Modules\Node,
            \Modules\BaseX;

        /*
         * automatically overriding query from Modules\Node
         */
        public function query(string $query): \DOMNodeList {
            $api = new $this->api;
            foreach ($api::$_queries as $_query => $_dom) {
                $fragment = new Node\Fragment($_query, $query);
                if ($fragment->isValid()) {
                    return (object) $this->xpath($_dom)->query($fragment->execute());
                }
            }

            $api::$_queries[$query] = $api->getDOMDocument($query);
            return (object) $this->xpath($api::$_queries[$query])->query(\sprintf("//%s/*", $api->root));
        }

        /*
         * automatically overriding evaluate from Modules\Node
         */        
        public function evaluate(string $query, string $function): int|float|string {
            $api = new $this->api;
            foreach ($api::$_queries as $_query => $_dom) {
                $fragment = new Node\Fragment($_query, $query);
                if ($fragment->isValid()) {
                    return (int) $this->xpath($_dom)->evaluate($function . $fragment->execute());
                }
            }

            return $api->getResponse($function . $query);
        }

        /*
         * additional basex fetching mapper based raw response
         */
        final public function fetch(array $validations = [], string $wrap = "(%s)"): string {
            return (string) (new $this->api)->getResponse((new Node\Find($this->path, $validations, $wrap))->execute());
        }

        /*
         * additional basex fetching all mapper based raw distinct response
         */
        final public function fetchAll(string $parameter, array $validations = [], string $wrap = "distinct-values(%s)"): array {
            if ($parameter !== $this->label) {
                $wrap = \sprintf($wrap, "%s/@" . $this->getField($parameter));
            }

            return (array) \explode(\PHP_EOL, $this->fetch($validations, $wrap));
        }
    }

}