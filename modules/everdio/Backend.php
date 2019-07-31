<?php
namespace Modules\Everdio {
    use \Components\Validation;
    use \Components\Validator;    
    use \Modules\Everdio\Library\ECms;
    use \Modules\Everdio;
    
    class Backend extends \Modules\Controller\Browser\Digest {
        public function __construct(array $server, array $request, \Components\Parser $parser) {
            parent::__construct($server, $request, $parser);
            $this->add("content", new Validation(false, [new Validator\IsString]));
            $this->add("realm", new Validation("2everdio", [new Validator\IsString]));
        }
        
        public function display() {            
            if (isset($this->digest)) {
                $account = new ECms\Account;
                $account->Account = $this->digest["username"];
                $account->Status = "active";
                $account->find();
                
                if (isset($account->AccountId) && $this->authorize($account->Account, $account->Password)) {
                    $session = new ECms\Session;
                    $session->AccountId = $account->AccountId;
                    $session->Opaque = $this->digest["opaque"];
                    $session->find();
                    $session->NonceCount = $session->NonceCount + 1;
                    $session->save();
                    
                    if ($this->isReturned($this->host)) {    
                        try {
                            if ($this->isRequested(["mapper"])) {
                                if ($this->isMethod("get")) {
                                    if ($this->isRouted("table.html")) {
                                        return (string) $this->execute("application/ecms/table");
                                    } elseif ($this->isRouted("editor.html")) {
                                        return (string) $this->execute("application/ecms/editor");
                                    } elseif ($this->isRouted("delete.html")) {
                                        $thisMapper = new $this->request["mapper"];               
                                        $thisMapper->store(array_intersect_key($this->request, array_flip($thisMapper->keys)));
                                        if (array_key_exists((string) $thisMapper, $this->delete)) {
                                            foreach ($this->delete[(string) $thisMapper] as $thatMapper) {
                                                $thatMapper = new $thatMapper;
                                                $thatMapper->store($thisMapper->restore($thisMapper->keys));
                                                $thatMapper->delete();
                                            }
                                        }    
                                        $thisMapper->delete();        
                                        http_response_code(200);
                                        die(header(sprintf("location: /table.html?mapper=%s", urlencode((string) $thisMapper))));                                    
                                    } else {
                                        return (string) $this->execute("application/ecms/dashboard");
                                    }
                                } elseif ($this->isMethod("post")) {
                                    if ($this->isRouted("save.html")) {
                                        $thisMapper = new $this->request["mapper"];
                                        $thisMapper->store($this->sanitize($this->request[(string) $thisMapper]));                        
                                        $thisMapper->save();
                                        http_response_code(200);
                                        die(header(sprintf("location: /editor.html?mapper=%s&%s", urlencode((string) $thisMapper), $thisMapper->query($thisMapper->keys))));                                    
                                    } else {
                                        http_response_code(200);
                                        die(header("location: /dashboard.html"));  
                                    }
                                } else {
                                    throw new Everdio\Event("invalid method");
                                }
                            } else {
                                throw new Everdio\Event("invalid mapper");
                            }
                        } catch (\Components\Event $event) {
                            http_response_code(500);
                            $this->content = $event->getMessage();
                            return (string) $this->execute("application/ecms/dashboard");
                        }
                    } else {
                        http_response_code(200);
                        return (string) $this->execute("application/ecms/dashboard");
                    }
                } else {
                    $this->unAuthorized($this->realm);
                    $this->content = "<h1>Invalid authentication</h1><p>You can peak around but there is nothing to do here buddy...</p>";
                    return (string) $this->execute("application/ecms/dashboard");
                }            
            } else {
                $this->unAuthorized($this->realm);
                $this->content = "<h1>Unauthorized access</h1><p>You can peak around but there is nothing to do here buddy...</p>";
                return (string) $this->execute("application/ecms/dashboard");
            }           
        }
    }
}

