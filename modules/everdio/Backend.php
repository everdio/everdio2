<?php
namespace Modules\Everdio {
    use \Components\Validation;
    use \Components\Validator;    
    use \Modules\Everdio\Library\ECms;    
    class Backend extends \Modules\Controller\Browser\Digest {
        public function __construct(array $server, array $request, \Components\Parser $parser) {
            parent::__construct($server, $request, $parser);
            $this->add("content", new Validation(false, [new Validator\IsString]));
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
                    
                    if ($this->isReturned($this->host)) {    
                        $this->content = sprintf("<h1>Authenticated</h1><p>It seems you are authenticated, %s, however, we keep an eye on you sir ...</p>", $account->Account);
                        try {
                            if ($this->isRouted("edistribution")) {                        
                                return (string) $this->execute("edistribution/index");
                            } elseif ($this->isRouted("ecms")) {
                                return (string) $this->execute("ecms/index");
                            } elseif ($this->isRouted("eprocessing")) {
                                return (string) $this->execute("eprocessing/index");
                            } else {
                                http_response_code(200);
                                return (string) $this->execute("dashboard");            
                            }
                        } catch (\Components\Event $event) {
                            http_response_code(500);
                            $this->content = $event->getMessage();
                            return (string) $this->execute("../dashboard");
                        }
                    } else {
                        http_response_code(200);
                        return (string) $this->execute("dashboard");
                    }
                    return (string) "";
                    
                } else {
                    $this->unAuthorized($this->realm);
                    $this->content = "<h1>Invalid authentication</h1><p>You can peak around but there is nothing to do here buddy...</p>";
                    return (string) $this->execute("dashboard");
                }            
            } else {
                $this->unAuthorized($this->realm);
                $this->content = "<h1>Unauthorized access</h1><p>You can peak around but there is nothing to do here buddy...</p>";
                return (string) $this->execute("dashboard");
            }           
        }
    }
}

