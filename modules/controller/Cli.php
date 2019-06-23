<?php
namespace Modules\Controller {
    use \Components\Validation;
    use \Components\Validator;

    final class Cli extends \Components\Core\Controller\Model {
        public function __construct(array $server, \Components\Parser $parser, array $request = NULL) {
            parent::__construct($parser);
            $this->add("server", new Validation($server, [new Validator\IsArray\Intersect\Key(["argv", "argc"])]));
            if ($this->server["argc"] >= 2) {
                $this->add("execute", new Validation($this->server["argv"][1], [new Validator\IsString\IsPath]));
                foreach (array_slice($this->server["argv"], 2) as $parameters) {
                    parse_str($parameters, $request);    
                    $this->request = $request;
                }
            }
        }
    }
}

