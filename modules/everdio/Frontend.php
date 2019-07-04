<?php
namespace Modules\Everdio {
    use \Components\Validation;
    use \Components\Validator;    
    use \Modules\Everdio\Library\ECms;
    class Frontend extends \Modules\Controller\Browser {
        public function __construct(array $server, array $request, \Components\Parser $parser) {
            parent::__construct($server, $request, $parser);
            
            $environment = new ECms\Environment;
            unset ($environment->Extension);
            $environment->Host = $this->host;
            $environment->Status = "active";
            $environment->find();
            
            if (!isset($this->arguments)) {
                $this->arguments = explode(DIRECTORY_SEPARATOR, $environment->Arguments);
            }
            
            $this->add((string) $environment, new Validation(false, array(new Validator\IsArray)));
            $this->{(string) $environment} = $environment->restore($environment->mapping);
        }

        public function display() {
            $environment = new ECms\Environment;
            $environment->store($this->{(string) $environment});
            
            if ($environment->Scheme !== "//" && $environment->Scheme !== $this->scheme) {
                return (string) header("Location:" . $environment->Scheme . $environment->Host . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $this->arguments), true, 301);
            }
            
            try {
                $redirect = new ECms\Redirect;
                unset ($redirect->Status);
                unset ($redirect->Hits);
                $redirect->Redirect = $environment->Scheme . $environment->Host . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $this->arguments);
                $redirect->find();
                if (isset($redirect->RedirectId)) {        
                    $redirect->Hits = $redirect->Hits + 1;
                    $redirect->save();
                    return (string) header("Location:" . $redirect->Routing, true, $redirect->Status);
                }            
                
                $document = new ECms\Documents;
                $document->reset($document->mapping);
                $this->add((string) $document, new Validation(false, array(new Validator\IsArray)));
                $document->EnvironmentId = $environment->EnvironmentId;
                $document->Routing = $environment->Scheme . $environment->Host . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $this->arguments);
                unset ($document->Methods);
                $document->Methods = [$this->method];
                $document->find();     

                if ($document->DocumentId !== 0) {
                    $cache = new \Components\File\Cache($this->root . DIRECTORY_SEPARATOR . $environment->MainPath . $environment->WwwPath . $environment->CachePath . DIRECTORY_SEPARATOR . $this->slug($document->Document));
                    if (!$cache->exists($document->CacheTtl)) {
                        $this->{(string) $document} = $document->restore($document->mapping);
                        
                        if (isset($document->Querystring)) {
                            parse_str($document->Querystring, $parameters);        
                            foreach ($parameters as $parameter => $value) {
                                $this->add($parameter, new Validation($value, array(new Validator\IsArray, new Validator\Isstring, new Validator\IsInteger)));
                            }
                        }

                        $tpl = new \Components\Core\Template;
                        
                        $properties = new ECms\Properties;
                        $properties->DocumentId = $document->DocumentId;
                        $properties->EnvironmentId = $environment->EnvironmentId;
                        
                        foreach ($properties->findAll() as $row) {
                            $property = new ECMs\Properties($row);
                            $tpl->{$property->Property} = $property->Content;                        
                        }                    
                        $translations = new ECms\Translations;
                        $translations->LanguageId = $document->LanguageId;
                        $translations->EnvironmentId = $environment->EnvironmentId;
                        foreach ($translations->findAll() as $row) {
                            $translation = new ECms\Translations($row);
                            $tpl->{$translation->Property} = $translation->Content;                        
                        }
                        
                        $cache->store($tpl->display($this->execute($environment->MainPath . DIRECTORY_SEPARATOR . $document->Route)));
                    }                                            
                    
                    http_response_code(200);
                    return (string) $cache->restore();
                } else {
                    //404 not found
                    $document = new ECms\Documents;
                    $this->add((string) $document, new Validation(false, array(new Validator\IsArray)));
                    $document->reset($document->mapping);
                    $document->EnvironmentId = $environment->EnvironmentId;
                    $document->Routing = $environment->Scheme . $environment->Host . DIRECTORY_SEPARATOR . $environment->PageNotFound;                    
                    $document->find();
                    $document->Content = sprintf($document->Content, $this->scheme, $this->host, implode(DIRECTORY_SEPARATOR, $this->arguments));    

                    $this->{(string) $document} = $document->restore($document->mapping);

                    parse_str($document->Querystring, $parameters);        

                    foreach ($parameters as $parameter => $value) {
                        $this->add($parameter, new Validation($value, array(new Validator\IsArray, new Validator\Isstring, new Validator\IsInteger)));
                    }

                    http_response_code(404); 
                    return (string) $this->execute($environment->MainPath . DIRECTORY_SEPARATOR . $document->Route);        
                }
            } catch (\Components\Event $event) {
                //500 error
                $document = new ECms\Documents;
                $this->add((string) $document, new Validation(false, array(new Validator\IsArray)));
                $document->reset($document->mapping);
                $document->EnvironmentId = $environment->EnvironmentId;
                $document->Routing = $environment->Scheme . $environment->Host . DIRECTORY_SEPARATOR . $environment->PageEvent;
                $document->find();
                $document->Content = sprintf($document->Content, $event->getMessage(), $event->getTraceAsString());
                
                $this->{(string) $document} = $document->restore($document->mapping);
                
                parse_str($document->Querystring, $parameters);      
                
                foreach ($parameters as $parameter => $value) {
                    $this->add($parameter, new Validation($value, array(new Validator\IsArray, new Validator\Isstring, new Validator\IsInteger)));
                } 

                http_response_code(500);                    
                return (string) $this->execute($document->Route);
            }            
        }
    }
}

