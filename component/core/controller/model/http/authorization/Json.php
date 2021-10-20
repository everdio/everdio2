<?php
namespace Component\Core\Controller\Model\Http\Authorization {
    class Json extends \Component\Core\Controller\Model\Http\Authorization {
        public function execute(string $path, array $parameters = [], string $include = "php") {
            try {
                \http_response_code(200);
                return (string) \json_encode(["status" => true, "body" => \json_decode(parent::execute($path, $parameters, $include))]);    
            } catch (\Error | \Exception $ex) {
                \http_response_code(500);
                return (string)  \json_encode(["status" => \get_class($ex), "body" => $ex->getMessage()]);
            }
        }
    }
}