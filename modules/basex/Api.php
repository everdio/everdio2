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
         * automatically overriding query from Modules\Node and checkfor previous results to see if we can re-use fragments of those DOMs
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
            return (new $this->api)->getResponse($function . $query);
        }

        /*
         * additional basex fetching mapper based raw response
         */
        final public function fetch(string $parameter, array $validations = [], string $wrap = "(%s)"): string {
            if ($parameter !== $this->label) {
                $wrap = \sprintf($wrap, "%s/@" . $this->getField($parameter));
            }
            
            return (string) (new $this->api)->getResponse((new Node\Find($this->path, $validations, $wrap))->execute());
        }

        /*
         * additional basex fetching all mapper based raw distinct response
         */
        final public function fetchAll(string $parameter, array $validations = [], string $wrap = "distinct-values(%s)"): array {
            return (array) \explode(\PHP_EOL, $this->fetch($parameter, $validations, $wrap));
        }
    }

}