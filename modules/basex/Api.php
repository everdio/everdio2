<?php

/*
 * overriding Modules\Node query() and evaluate()
 */

namespace Modules\BaseX {

    trait Api {

        use \Modules\Node,
            \Modules\BaseX;

        public function query(string $query): \DOMNodeList {
            $api = new $this->api;
            foreach ($api::$_queries as $_query => $_dom) {
                $fragment = new \Modules\Node\Fragment($_query, $query);
                if ($fragment->isValid()) {
                    return (object) $this->xpath($_dom)->query($fragment->execute());
                }
            }

            $api::$_queries[$query] = $api->getDOMDocument($query);
            return (object) $this->xpath($api::$_queries[$query])->query(\sprintf("//%s/*", $api->root));
        }

        public function evaluate(string $query, string $function): int|float|string {
            $api = new $this->api;
            foreach ($api::$_queries as $_query => $_dom) {
                $fragment = new \Modules\Node\Fragment($_query, $query);
                if ($fragment->isValid()) {
                    return (int) $this->xpath($_dom)->evaluate($function . $fragment->execute());
                }
            }

            return $api->getResponse($function . $query);
        }
    }

}