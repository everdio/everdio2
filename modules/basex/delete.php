<?php
if ($this instanceof Component\Core\Controller\Model\Cli) {
    $this->echo("BaseX Curl " . $this->basex->host . \DIRECTORY_SEPARATOR . $this->request->database, ["bold", "white"]);
    $this->echo(" deleting ", ["bold", "green"]);
    $this->echo(\sprintf("%s .. ", $this->request->resource), ["bold", "yellow"]);
    
    $curl = new \Component\Caller\Curl;
    $curl->setopt_array([
        \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC,
        \CURLOPT_USERPWD => $this->basex->username . ":" . $this->basex->password,
        \CURLOPT_URL => $this->basex->host . \DIRECTORY_SEPARATOR . $this->request->database . \DIRECTORY_SEPARATOR . $this->request->resource]);    
    $curl->delete();
    $this->echo($curl->execute(), ["white", "bold"]);
    $this->break(1);            
}