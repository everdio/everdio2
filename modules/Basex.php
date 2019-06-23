<?php
namespace Modules {
    use \Components\Validation;
    use \Components\Validator;         
    use \Components\Core\Library;
    use \Modules\Node;
    use \Components\Resource\Dom\Xml;
    class Basex extends \Components\Core\Caller\Curl {
        public function __construct(string $query) {
            $this->add("url", new Validation(false, [new Validator\IsString\IsUrl]));
            $this->add("username", new Validation(false, [new Validator\IsString]));
            $this->add("password", new Validation(false, [new Validator\IsString]));
            $this->add("query", new Validation(urlencode($query), [new Validator\IsString\IsPath]));
            parent::__construct();
        }
 
        public function store(array $values) {
            parent::store($values);
            $this->setopt_array(array(CURLOPT_HTTPAUTH => CURLAUTH_BASIC, CURLOPT_USERPWD => sprintf("%s:%s", $this->username, $this->password), CURLOPT_FOLLOWLOCATION => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_URL => sprintf("%s?query=%s", $this->url, $this->query)));
        }

        public function get(string $id) {
            $index = new \Components\Index($id);
            $index->store($dom = new Xml($this->execute(), "1.0", "UTF-8", LIBXML_NOERROR | LIBXML_NONET | LIBXML_NOWARNING));
        }
        
        public function generate(string $id, string $root, string $namespace) {
            try {
                $dom = new Xml($this->execute(), "1.0", "UTF-8", LIBXML_NOERROR | LIBXML_NONET | LIBXML_NOWARNING);
                $library = new Library($dom);
                $library->id = $id;
                $library->root = $root;
                $library->namespace = $namespace;
                $library->extend = "\Modules\Node";
                $library->create();         

                foreach ($dom->execute("//*") as $node) {
                    $model = new Node\Model($dom);      
                    $model->root = $root;
                    $model->node = $node;
                    $model->namespace = sprintf("%s\%s", $library->namespace, $library->labelize($library->id));
                    $model->extend = sprintf("\%s", $model->namespace);
                    $model->setup();   
                    $model->create();
                }                 
                return (string) "done";
            } catch (\Components\Event $event) {
                return (string) $event->getMessage();
            }
        }
    }
}
